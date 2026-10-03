<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vigencia extends Model
{
    protected $table = 'vigencias';
    protected $primaryKey = 'IdVigencia';
    public $incrementing = false;
    public $timestamps = false;
}
