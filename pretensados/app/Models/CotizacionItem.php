<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Tabla `cotizaciones_items`: no tiene clave primaria (se borra y reinserta por versión, como en el VB). */
class CotizacionItem extends Model
{
    protected $table = 'cotizaciones_items';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function detalleArticulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class, 'Articulo', 'CODIGO');
    }
}
