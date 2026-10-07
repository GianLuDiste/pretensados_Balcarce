<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Tabla `cotizaciones_estados`: PD Pendiente, AT/AP Adjudicada Total/Parcial, NA No Adjudicada, RP Referente Pedido Precios. */
class CotizacionEstado extends Model
{
    protected $table = 'cotizaciones_estados';
    protected $primaryKey = 'idStatus';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    /** Clase CSS de la etiqueta de cada estado (public/css/brand.css → .badge.est-*). */
    public const CLASES = ['PD' => 'est-pd', 'AT' => 'est-at', 'AP' => 'est-ap', 'NA' => 'est-na', 'RP' => 'est-rp'];

    public static function clase(?string $codigo): string
    {
        return self::CLASES[$codigo] ?? '';
    }
}
