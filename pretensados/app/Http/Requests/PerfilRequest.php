<?php

namespace App\Http\Requests;

use App\Models\Acceso;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Se controla en las rutas: acceso:perfiles,M
    }

    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('perfiles', 'nombre')->ignore($this->route('perfil'))],
            'descripcion' => ['nullable', 'string', 'max:255'],
            // accesos[id_acceso] = '' (sin acceso) | C | M
            'accesos'     => ['nullable', 'array'],
            'accesos.*'   => ['nullable', Rule::in(array_keys(Acceso::NIVELES))],
            'usuarios'    => ['nullable', 'array'],
            'usuarios.*'  => ['integer', Rule::exists('users', 'id')],
        ];
    }

    public function attributes(): array
    {
        return ['descripcion' => 'descripción', 'accesos.*' => 'nivel de acceso', 'usuarios.*' => 'usuario'];
    }

    public function messages(): array
    {
        return ['nombre.unique' => 'Ya existe un perfil con ese nombre.'];
    }
}
