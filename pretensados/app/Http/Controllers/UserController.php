<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $t = '%' . $request->q . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $t)->orWhere('username', 'like', $t)->orWhere('email', 'like', $t));
            })
            ->orderBy('name')->paginate(25)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.form', ['usuario' => new User(['activo' => true, 'permisos' => 'C'])]);
    }

    public function store(UserRequest $request)
    {
        $d = $request->validated();

        User::create([
            'name'     => $d['name'],
            'username' => $d['username'],
            'email'    => $d['email'],
            'telefono' => $d['telefono'] ?? null,
            'password' => $d['password'],
            'es_admin' => $request->boolean('es_admin'),
            'activo'   => $request->boolean('activo'),
            'permisos' => $this->armarPermisos($d['permisos'] ?? []),
        ]);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.form', compact('usuario'));
    }

    public function update(UserRequest $request, User $usuario)
    {
        $d = $request->validated();
        $esAdmin = $request->boolean('es_admin');
        $activo  = $request->boolean('activo');

        if ($usuario->esUltimoAdminActivo() && (! $esAdmin || ! $activo)) {
            return back()->withInput()->withErrors(['es_admin' => 'Es el único administrador activo: no se le puede quitar el rol ni desactivarlo.']);
        }
        if ($usuario->is($request->user()) && ! $activo) {
            return back()->withInput()->withErrors(['activo' => 'No podés desactivar tu propio usuario.']);
        }

        $datos = [
            'name'     => $d['name'],
            'username' => $d['username'],
            'email'    => $d['email'],
            'telefono' => $d['telefono'] ?? null,
            'es_admin' => $esAdmin,
            'activo'   => $activo,
            'permisos' => $this->armarPermisos($d['permisos'] ?? []),
        ];
        if (! empty($d['password'])) {
            $datos['password'] = $d['password'];
        }
        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No podés eliminar tu propio usuario.']);
        }
        if ($usuario->esUltimoAdminActivo()) {
            return back()->withErrors(['usuario' => 'No se puede eliminar al único administrador activo.']);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado.');
    }

    /** Ordena las letras como el VB ("ABMCI"); si tiene cualquier permiso, incluye Consulta (el VB cerraba el form sin "C"). */
    private function armarPermisos(array $marcados): string
    {
        $marcados = array_filter($marcados);
        if ($marcados && ! in_array('C', $marcados, true)) {
            $marcados[] = 'C';
        }

        return implode('', array_filter(['A', 'B', 'M', 'C', 'I'], fn ($l) => in_array($l, $marcados, true)));
    }
}
