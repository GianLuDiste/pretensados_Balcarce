<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Reemplaza ValidoDatos() y Text1_KeyPress del VB: la razón social es obligatoria,
 * se guarda en mayúsculas y no puede repetirse; localidad, provincia e IVA deben existir.
 */
class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Los permisos A/M se controlan en las rutas (middleware permiso).
    }

    protected function prepareForValidation(): void
    {
        $limpio = collect($this->only([
            'razon_social', 'direccion', 'telefono', 'fax', 'cod_postal', 'cuit', 'contacto', 'email',
        ]))->map(fn ($v) => is_string($v) ? trim($v) : $v);

        if (is_string($limpio->get('razon_social'))) {
            $limpio['razon_social'] = mb_strtoupper($limpio['razon_social']);   // UCase(Text1)
        }

        $this->merge($limpio->all());
    }

    public function rules(): array
    {
        $codigo = $this->route('cliente')?->CODIGO;

        return [
            'razon_social' => ['required', 'string', 'max:100',
                Rule::unique('clientes', 'RAZON-SOCIAL')->ignore($codigo, 'CODIGO')],
            'direccion'    => ['nullable', 'string', 'max:50'],
            'telefono'     => ['nullable', 'string', 'max:20'],
            'fax'          => ['nullable', 'string', 'max:20'],
            'localidad'    => ['required', 'integer', Rule::exists('ciudades', 'CODIGO')],
            'cod_postal'   => ['nullable', 'integer', 'min:0'],
            'provincia'    => ['required', 'integer', Rule::exists('provincias', 'CODIGO')],
            'iva'          => ['required', 'integer', Rule::exists('iva', 'CODIGO')],
            'cuit'         => ['nullable', 'string', 'max:20'],
            'contacto'     => ['nullable', 'string', 'max:50'],
            'email'        => ['nullable', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'razon_social' => 'razón social', 'direccion' => 'dirección', 'telefono' => 'teléfono',
            'cod_postal' => 'código postal', 'iva' => 'condición de IVA', 'email' => 'e-mail',
        ];
    }

    public function messages(): array
    {
        return ['razon_social.unique' => 'Ya existe un cliente con esa razón social.'];
    }
}
