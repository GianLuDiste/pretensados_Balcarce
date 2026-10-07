<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerfilRequest;
use App\Models\Acceso;
use App\Models\Perfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** ABM de perfiles y de sus relaciones: accesos del perfil (con nivel) y usuarios del perfil. */
class PerfilController extends Controller
{
    public function index()
    {
        $perfiles = Perfil::withCount(['accesos', 'usuarios'])->orderByDesc('es_admin')->orderBy('nombre')->get();

        return view('perfiles.index', compact('perfiles'));
    }

    public function create()
    {
        return view('perfiles.form', $this->datosVista(new Perfil()));
    }

    public function store(PerfilRequest $request)
    {
        $d = $request->validated();
        $usuarios = $this->idsUsuarios($d);

        $perfil = DB::transaction(function () use ($d, $usuarios) {
            $p = Perfil::create(['nombre' => $d['nombre'], 'descripcion' => $d['descripcion'] ?? null]);
            $p->accesos()->sync($this->nivelesParaSync($d));
            $p->usuarios()->sync($usuarios);
            return $p;
        });

        return redirect()->route('perfiles.edit', $perfil)->with('ok', "Perfil «{$perfil->nombre}» creado.");
    }

    public function edit(Perfil $perfil)
    {
        return view('perfiles.form', $this->datosVista($perfil));
    }

    public function update(PerfilRequest $request, Perfil $perfil)
    {
        $d = $request->validated();
        $usuarios = $this->idsUsuarios($d);

        if ($perfil->es_admin) {
            if (! $request->user()->esAdmin()) {
                return back()->withInput()->withErrors(['perfil' => 'Solo un administrador puede modificar el perfil Administrador.']);
            }
            if (! User::whereIn('id', $usuarios)->where('activo', true)->exists()) {
                return back()->withInput()->withErrors(['usuarios' => 'El perfil Administrador debe tener al menos un usuario activo.']);
            }
        }

        DB::transaction(function () use ($perfil, $d, $usuarios) {
            $perfil->update(['nombre' => $d['nombre'], 'descripcion' => $d['descripcion'] ?? null]);
            if (! $perfil->es_admin) {                 // el Administrador tiene acceso total: no lleva accesos
                $perfil->accesos()->sync($this->nivelesParaSync($d));
            }
            $perfil->usuarios()->sync($usuarios);
        });

        return redirect()->route('perfiles.edit', $perfil)->with('ok', "Perfil «{$perfil->nombre}» actualizado.");
    }

    public function destroy(Perfil $perfil)
    {
        if ($perfil->es_admin) {
            return back()->withErrors(['perfil' => 'El perfil Administrador es del sistema y no se puede eliminar.']);
        }

        $perfil->delete();   // acceso_perfil y perfil_user se borran en cascada

        return redirect()->route('perfiles.index')->with('ok', "Perfil «{$perfil->nombre}» eliminado.");
    }

    // ------------------------------------------------------------------

    /** accesos[id] = C|M|'' → [id => ['nivel' => C|M]] sin los vacíos. */
    private function nivelesParaSync(array $d): array
    {
        return collect($d['accesos'] ?? [])
            ->filter(fn ($nivel) => in_array($nivel, array_keys(Acceso::NIVELES), true))
            ->mapWithKeys(fn ($nivel, $id) => [(int) $id => ['nivel' => $nivel]])
            ->all();
    }

    private function idsUsuarios(array $d): array
    {
        return array_values(array_unique(array_map('intval', $d['usuarios'] ?? [])));
    }

    private function datosVista(Perfil $perfil): array
    {
        $perfil->loadMissing(['accesos', 'usuarios']);
        $soloLectura = ! auth()->user()->puedeModificar('perfiles') || ($perfil->es_admin && ! auth()->user()->esAdmin());

        return [
            'perfil'      => $perfil,
            'accesos'     => Acceso::ordenados(),
            'niveles'     => $perfil->nivelesPorCodigo(),
            'usuarios'    => User::orderByDesc('activo')->orderBy('name')->get(),
            'soloLectura' => $soloLectura,
        ];
    }
}
