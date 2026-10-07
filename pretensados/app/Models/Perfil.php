<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** Conjunto de accesos que se asigna a usuarios. El perfil con es_admin tiene acceso total. */
class Perfil extends Model
{
    protected $table = 'perfiles';
    protected $fillable = ['nombre', 'descripcion'];   // es_admin no se asigna desde formularios
    protected $casts = ['es_admin' => 'boolean'];

    public function accesos(): BelongsToMany
    {
        return $this->belongsToMany(Acceso::class, 'acceso_perfil')->withPivot('nivel');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'perfil_user');
    }

    /** [codigo => nivel] de este perfil. */
    public function nivelesPorCodigo(): array
    {
        return $this->accesos->pluck('pivot.nivel', 'codigo')->all();
    }
}
