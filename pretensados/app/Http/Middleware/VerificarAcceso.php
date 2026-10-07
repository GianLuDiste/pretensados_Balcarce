<?php

namespace App\Http\Middleware;

use App\Models\Acceso;
use Closure;
use Illuminate\Http\Request;

/**
 * Uso en rutas:
 *   ->middleware('acceso:clientes')     consulta (alcanza con nivel C o M)
 *   ->middleware('acceso:clientes,M')   modificación (alta, cambios, baja)
 */
class VerificarAcceso
{
    public function handle(Request $request, Closure $next, string $codigo, string $nivel = Acceso::CONSULTA)
    {
        $user = $request->user();
        $ok = $user && $user->activo
            && ($nivel === Acceso::MODIFICACION ? $user->puedeModificar($codigo) : $user->puedeVer($codigo));

        if (! $ok) {
            $nombre = Acceso::where('codigo', $codigo)->value('nombre') ?? $codigo;
            abort(403, $nivel === Acceso::MODIFICACION
                ? "No tiene permiso de modificación en {$nombre}."
                : "No tiene acceso a {$nombre}.");
        }

        return $next($request);
    }
}
