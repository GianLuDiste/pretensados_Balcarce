<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'CODIGO';
    public $incrementing = true;   // CODIGO es auto_increment en MySQL
    public $timestamps = false;

    /** Tablas que referencian al cliente: si tiene filas en alguna, no se puede borrar. */
    public const REFERENCIAS = [
        'cotizaciones'    => 'IdCliente',
        'facturas'        => 'CLIENTE',
        'remitos'         => 'CLIENTE',
        'ordenes compra'  => 'CLIENTE',
        'cta cte'         => 'CLIENTE',
        'movimientos'     => 'CLIENTE',
    ];

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'LOCALIDAD', 'CODIGO');
    }

    // No puede llamarse provincia(): PHP no distingue mayúsculas y chocaría con la columna PROVINCIA.
    public function prov(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'PROVINCIA', 'CODIGO');
    }

    public function condicionIva(): BelongsTo
    {
        return $this->belongsTo(CondicionIva::class, 'IVA', 'CODIGO');
    }

    /** Primera tabla donde el cliente tiene movimientos, o null si no tiene (Verifico() del VB). */
    public function tablaConMovimientos(): ?string
    {
        foreach (self::REFERENCIAS as $tabla => $columna) {
            if (DB::table($tabla)->where($columna, $this->CODIGO)->exists()) {
                return $tabla;
            }
        }

        return null;
    }

    // Columnas con guion: se exponen como $cliente->razon_social, ->email, ->cod_postal
    protected function razonSocial(): Attribute { return Attribute::get(fn ($v, $a) => $a['RAZON-SOCIAL'] ?? null); }
    protected function email(): Attribute       { return Attribute::get(fn ($v, $a) => $a['E-MAIL'] ?? null); }
    protected function codPostal(): Attribute   { return Attribute::get(fn ($v, $a) => $a['COD-POSTAL'] ?? null); }
}
