<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Se controla en las rutas: acceso:usuarios,M
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');       // null al crear
        $alta = $usuario === null;

        return [
            'name'       => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'alpha_dash:ascii', Rule::unique('users', 'username')->ignore($usuario)],
            'email'      => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($usuario)],
            'telefono'   => ['nullable', 'string', 'max:30'],
            'password'   => [$alta ? 'required' : 'nullable', 'confirmed', Password::min(8)],
            'activo'     => ['nullable', 'boolean'],
            'perfiles'   => ['nullable', 'array'],
            'perfiles.*' => ['integer', Rule::exists('perfiles', 'id')],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre completo', 'username' => 'nombre de usuario', 'email' => 'email',
            'telefono' => 'teléfono', 'password' => 'contraseña', 'perfiles.*' => 'perfil',
        ];
    }
}
