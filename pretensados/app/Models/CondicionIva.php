<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Tabla IVA del VB: condición frente al IVA (Resp. Inscripto, Monotributo, Consumidor Final...). */
class CondicionIva extends Model
{
    protected $table = 'iva';
    protected $primaryKey = 'CODIGO';
    public $incrementing = false;
    public $timestamps = false;
}
