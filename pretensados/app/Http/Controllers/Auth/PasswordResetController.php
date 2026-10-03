<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function formOlvide()
    {
        return view('auth.forgot-password');
    }

    public function enviarEnlace(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        // 'activo' => 1: un usuario inactivo no puede recuperar la clave.
        $estado = Password::sendResetLink(['email' => $request->email, 'activo' => 1]);

        if ($estado === Password::RESET_THROTTLED) {
            return back()->withInput()->withErrors(['email' => 'Esperá un minuto antes de pedir otro enlace.']);
        }

        // Misma respuesta exista o no el email (evita averiguar qué emails están registrados).
        return back()->with('estado', 'Si el email está registrado, te enviamos un enlace para restablecer la contraseña.');
    }

    public function formRestablecer(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function restablecer(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $estado = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token') + ['activo' => 1],
            function ($user, $password) {
                $user->forceFill([
                    'password'       => $password,          // el cast 'hashed' la encripta
                    'remember_token' => Str::random(60),    // invalida los "recordarme" anteriores
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($estado === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('estado', 'Contraseña actualizada. Ya podés ingresar.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'El enlace no es válido o venció. Pedí uno nuevo.']);
    }
}
