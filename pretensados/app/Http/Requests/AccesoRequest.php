<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccesoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Se controla en las rutas: acceso:accesos,M
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->codigo)) {
            $this->merge(['codigo' => strtolower(trim($this->codigo))]);
        }
    }

    public function rules(): array
    {
        $acceso = $this->route('acceso');

        return [
            // El código lo usa el sistema (rutas y menú): solo se carga en el alta.
            'codigo'      => $acceso ? ['prohibited'] : ['required', 'string', 'max:50', 'regex:/^[a-z][a-z0-9_-]*$/', Rule::unique('accesos', 'codigo')],
            'nombre'      => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'orden'       => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function attributes(): array
    {
        return ['codigo' => 'código', 'descripcion' => 'descripción'];
    }

    public function messages(): array
    {
        return [
            'codigo.regex'      => 'El código va en minúsculas, sin espacios: letras, números, guion o guion bajo (ej.: ordenes_compra).',
            'codigo.unique'     => 'Ya existe un acceso con ese código.',
            'codigo.prohibited' => 'El código no se puede cambiar.',
        ];
    }
}
