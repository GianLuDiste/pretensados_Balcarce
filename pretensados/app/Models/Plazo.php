<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plazo extends Model
{
    protected $table = 'plazos';
    protected $primaryKey = 'IdPlazo';
    public $incrementing = false;
    public $timestamps = false;
}
