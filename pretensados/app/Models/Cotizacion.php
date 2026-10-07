<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabla `cotizaciones`. PK compuesta (Nro_Cotizacion, Version).
 * Eloquent no soporta claves compuestas: se declara sin autoincremento y
 * toda lectura/escritura se hace filtrando por las dos columnas (ver buscar()).
 */
class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'Nro_Cotizacion';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = ['Fecha' => 'datetime'];

    public static function buscar(int $nro, int $ver): self
    {
        return static::where('Nro_Cotizacion', $nro)->where('Version', $ver)->firstOrFail();
    }

    /** Ítems de ESTA versión (no se usa hasMany porque la clave es compuesta). */
    public function items(): Builder
    {
        return CotizacionItem::query()
            ->where('Nro_Cotizacion', $this->Nro_Cotizacion)
            ->where('Version', $this->Version);
    }

    public function cliente(): BelongsTo     { return $this->belongsTo(Cliente::class, 'IdCliente', 'CODIGO'); }
    public function plazo(): BelongsTo       { return $this->belongsTo(Plazo::class, 'IdPlazo_Entrega', 'IdPlazo'); }
    public function formaPago(): BelongsTo   { return $this->belongsTo(FormaPago::class, 'IdForma_Pago', 'CODIGO'); }
    public function vigencia(): BelongsTo    { return $this->belongsTo(Vigencia::class, 'IdVigencia', 'IdVigencia'); }
    public function estado(): BelongsTo      { return $this->belongsTo(CotizacionEstado::class, 'IdStatus', 'idStatus'); }
}
