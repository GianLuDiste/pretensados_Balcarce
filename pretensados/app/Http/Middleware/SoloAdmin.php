<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SoloAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->es_admin) {
            abort(403, 'Solo los administradores pueden gestionar usuarios.');
        }

        return $next($request);
    }
}
