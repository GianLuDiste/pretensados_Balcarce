<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /** Mismas letras que el VB (Permiso = "ABMCI"). */
    public const PERMISOS = [
        'C' => 'Consulta',
        'A' => 'Alta',
        'M' => 'Modificación',
        'B' => 'Baja',
        'I' => 'Impresión',
    ];

    protected $fillable = ['name', 'username', 'email', 'telefono', 'password', 'es_admin', 'permisos', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',   // se encripta sola con bcrypt al asignarla
        'es_admin' => 'boolean',
        'activo'   => 'boolean',
    ];

    /** El administrador puede todo; el resto, según sus letras. */
    public function puede(string $permiso): bool
    {
        return $this->es_admin || str_contains((string) $this->permisos, $permiso);
    }

    /** ¿Es el único administrador activo que queda? */
    public function esUltimoAdminActivo(): bool
    {
        return $this->es_admin && $this->activo
            && ! static::where('es_admin', true)->where('activo', true)->where('id', '!=', $this->id)->exists();
    }
}
