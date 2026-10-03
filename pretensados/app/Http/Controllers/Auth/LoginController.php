<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private const MAX_INTENTOS = 5;

    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Freno a los intentos repetidos (por usuario + IP).
        $clave = Str::lower($datos['username']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($clave, self::MAX_INTENTOS)) {
            $seg = RateLimiter::availableIn($clave);
            throw ValidationException::withMessages([
                'username' => "Demasiados intentos. Probá de nuevo en {$seg} segundos.",
            ]);
        }

        $ok = Auth::attempt(
            ['username' => $datos['username'], 'password' => $datos['password'], 'activo' => 1],
            $request->boolean('recordarme')
        );

        if (! $ok) {
            RateLimiter::hit($clave, 60);
            // Mensaje único: no revela si el usuario existe, si la clave falló o si está inactivo.
            throw ValidationException::withMessages([
                'username' => 'Usuario o contraseña incorrectos, o el usuario está inactivo.',
            ]);
        }

        RateLimiter::clear($clave);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
