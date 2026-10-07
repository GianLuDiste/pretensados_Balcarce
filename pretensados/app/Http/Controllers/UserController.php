<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Acceso;
use App\Models\Perfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::with('perfiles')
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . $request->q . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $t)->orWhere('username', 'like', $t)->orWhere('email', 'like', $t));
            })
            ->when($request->filled('perfil'), fn ($q) => $q->whereHas('perfiles', fn ($p) => $p->where('perfiles.id', $request->perfil)))
            ->orderBy('name')->paginate(25)->withQueryString();

        return view('usuarios.index', ['usuarios' => $usuarios, 'perfiles' => Perfil::orderBy('nombre')->get()]);
    }

    public function create()
    {
        return view('usuarios.form', $this->datosVista(new User(['activo' => true])));
    }

    public function store(UserRequest $request)
    {
        $d = $request->validated();
        $perfiles = $this->idsPerfiles($d);

        if ($error = $this->controlAdmin($request->user(), null, $perfiles)) {
            return back()->withInput()->withErrors(['perfiles' => $error]);
        }

        DB::transaction(function () use ($d, $request, $perfiles) {
            $u = User::create([
                'name'     => $d['name'],
                'username' => $d['username'],
                'email'    => $d['email'],
                'telefono' => $d['telefono'] ?? null,
                'password' => $d['password'],
                'activo'   => $request->boolean('activo'),
            ]);
            $u->perfiles()->sync($perfiles);
        });

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.form', $this->datosVista($usuario));
    }

    public function update(UserRequest $request, User $usuario)
    {
        $d = $request->validated();
        $activo = $request->boolean('activo');
        $perfiles = $this->idsPerfiles($d);

        if ($error = $this->controlAdmin($request->user(), $usuario, $perfiles)) {
            return back()->withInput()->withErrors(['perfiles' => $error]);
        }
        $quedaAdmin = Perfil::whereIn('id', $perfiles)->where('es_admin', true)->exists();
        if ($usuario->esUltimoAdminActivo() && (! $quedaAdmin || ! $activo)) {
            return back()->withInput()->withErrors(['perfiles' => 'Es el único administrador activo: no se le puede quitar el perfil Administrador ni desactivarlo.']);
        }
        if ($usuario->is($request->user()) && ! $activo) {
            return back()->withInput()->withErrors(['activo' => 'No podés desactivar tu propio usuario.']);
        }

        $datos = [
            'name'     => $d['name'],
            'username' => $d['username'],
            'email'    => $d['email'],
            'telefono' => $d['telefono'] ?? null,
            'activo'   => $activo,
        ];
        if (! empty($d['password'])) {
            $datos['password'] = $d['password'];
        }

        DB::transaction(function () use ($usuario, $datos, $perfiles) {
            $usuario->update($datos);
            $usuario->perfiles()->sync($perfiles);
        });

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No podés eliminar tu propio usuario.']);
        }
        if ($usuario->esAdmin() && ! $request->user()->esAdmin()) {
            return back()->withErrors(['usuario' => 'Solo un administrador puede eliminar a otro administrador.']);
        }
        if ($usuario->esUltimoAdminActivo()) {
            return back()->withErrors(['usuario' => 'No se puede eliminar al único administrador activo.']);
        }

        $usuario->delete();   // perfil_user se borra en cascada

        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado.');
    }

    // ------------------------------------------------------------------

    private function idsPerfiles(array $d): array
    {
        return array_values(array_unique(array_map('intval', $d['perfiles'] ?? [])));
    }

    /**
     * Solo un administrador puede: dar o quitar el perfil Administrador, y modificar a un usuario administrador
     * (si no, alguien con acceso a Usuarios podría tomar la cuenta de un administrador o darse acceso total).
     */
    private function controlAdmin(User $actor, ?User $usuario, array $perfilesNuevos): ?string
    {
        if ($actor->esAdmin()) {
            return null;
        }
        if ($usuario?->esAdmin()) {
            return 'Solo un administrador puede modificar a otro administrador.';
        }
        if (Perfil::whereIn('id', $perfilesNuevos)->where('es_admin', true)->exists()) {
            return 'Solo un administrador puede asignar el perfil Administrador.';
        }

        return null;
    }

    private function datosVista(User $usuario): array
    {
        return [
            'usuario'  => $usuario,
            'perfiles' => Perfil::with('accesos')->orderByDesc('es_admin')->orderBy('nombre')->get(),
            'accesos'  => Acceso::ordenados(),
            'actorEsAdmin' => auth()->user()->esAdmin(),
        ];
    }
}
