<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'formas de pago';
    protected $primaryKey = 'CODIGO';
    public $incrementing = false;
    public $timestamps = false;
}
