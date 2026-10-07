<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccesoRequest;
use App\Models\Acceso;

/** ABM de accesos: una fila por opción del menú. */
class AccesoController extends Controller
{
    public function index()
    {
        $accesos = Acceso::withCount('perfiles')->orderBy('orden')->orderBy('nombre')->get();

        return view('accesos.index', compact('accesos'));
    }

    public function create()
    {
        $orden = (int) Acceso::max('orden') + 10;

        return view('accesos.form', ['acceso' => new Acceso(['orden' => $orden])]);
    }

    public function store(AccesoRequest $request)
    {
        $d = $request->validated();
        $a = Acceso::create($d + ['orden' => 0]);

        return redirect()->route('accesos.index')->with('ok', "Acceso «{$a->nombre}» creado.");
    }

    public function edit(Acceso $acceso)
    {
        return view('accesos.form', ['acceso' => $acceso->loadCount('perfiles')]);
    }

    public function update(AccesoRequest $request, Acceso $acceso)
    {
        $d = $request->validated();
        $acceso->update(['nombre' => $d['nombre'], 'descripcion' => $d['descripcion'] ?? null, 'orden' => $d['orden'] ?? 0]);

        return redirect()->route('accesos.index')->with('ok', "Acceso «{$acceso->nombre}» actualizado.");
    }

    public function destroy(Acceso $acceso)
    {
        if ($acceso->esDelSistema()) {
            return back()->withErrors(['acceso' => "«{$acceso->nombre}» es un acceso del sistema y no se puede eliminar."]);
        }
        if ($n = $acceso->perfiles()->count()) {
            return back()->withErrors(['acceso' => "«{$acceso->nombre}» está asignado a {$n} perfil(es): quitalo de esos perfiles antes de eliminarlo."]);
        }

        $acceso->delete();

        return redirect()->route('accesos.index')->with('ok', "Acceso «{$acceso->nombre}» eliminado.");
    }
}
