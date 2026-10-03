<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->es_admin;
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
            'es_admin'   => ['nullable', 'boolean'],
            'activo'     => ['nullable', 'boolean'],
            'permisos'   => ['nullable', 'array'],
            'permisos.*' => ['nullable', Rule::in(array_keys(User::PERMISOS))],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre completo', 'username' => 'nombre de usuario', 'email' => 'email',
            'telefono' => 'teléfono', 'password' => 'contraseña', 'permisos.*' => 'permiso',
        ];
    }
}
