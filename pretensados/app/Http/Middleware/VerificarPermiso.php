<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

/** Uso en rutas: ->middleware('permiso:B')  (equivale a InStr(Permiso, "B") del VB). */
class VerificarPermiso
{
    public function handle(Request $request, Closure $next, string $permiso)
    {
        $user = $request->user();

        if (! $user || ! $user->activo || ! $user->puede($permiso)) {
            $accion = strtolower(User::PERMISOS[$permiso] ?? $permiso);
            abort(403, "No tiene autorización para: {$accion}.");
        }

        return $next($request);
    }
}
