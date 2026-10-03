<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';
    protected $primaryKey = 'CODIGO';
    public $incrementing = false;
    public $timestamps = false;
}
