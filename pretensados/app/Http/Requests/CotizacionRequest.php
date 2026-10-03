<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Reemplaza Validar() y ValidarItem() del VB: cliente, plazo, forma de pago y
 * vigencia deben existir; cada ítem necesita artículo, cantidad, % y precio numéricos.
 */
class CotizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // En el VB: Permiso = "ABMCI" fijo. Acá se puede enganchar auth/policies.
    }

    public function rules(): array
    {
        return [
            'nro_cotizacion'   => ['nullable', 'integer', Rule::exists('cotizaciones', 'Nro_Cotizacion')],
            'fecha'            => ['required', 'date'],
            'porc_recargo'     => ['nullable', 'numeric', 'min:0'],
            'id_cliente'       => ['required', 'integer', Rule::exists('clientes', 'CODIGO')],
            'contacto'         => ['nullable', 'string', 'max:100'],
            'id_plazo_entrega' => ['required', 'integer', Rule::exists('plazos', 'IdPlazo')],
            'lugar_entrega'    => ['nullable', 'string', 'max:100'],
            'flete'            => ['nullable', 'string', 'max:100'],
            'observaciones'    => ['nullable', 'string', 'max:100'],
            'id_forma_pago'    => ['required', 'integer', Rule::exists('formas de pago', 'CODIGO')],
            'obs_fpago'        => ['nullable', 'string', 'max:50'],
            'id_vigencia'      => ['required', 'integer', Rule::exists('vigencias', 'IdVigencia')],
            'porc_iva'         => ['nullable', 'numeric', 'min:0'],
            'porc_iibb'        => ['nullable', 'numeric', 'min:0'],

            'items'                  => ['required', 'array', 'min:1'],
            'items.*.articulo'       => ['required', 'integer', Rule::exists('articulos', 'CODIGO')],
            'items.*.cantidad'       => ['required', 'integer', 'min:1'],
            'items.*.porc_recargo'   => ['required', 'numeric'],
            'items.*.kgs_unit'       => ['nullable', 'numeric', 'min:0'],
            'items.*.pcio_unit'      => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_cliente' => 'cliente', 'id_plazo_entrega' => 'plazo de entrega',
            'id_forma_pago' => 'forma de pago', 'id_vigencia' => 'vigencia',
            'items.*.articulo' => 'artículo', 'items.*.cantidad' => 'cantidad',
            'items.*.porc_recargo' => '% s/costo', 'items.*.pcio_unit' => 'precio unitario',
        ];
    }
}
