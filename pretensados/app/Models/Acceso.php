<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** Unidad de función: una opción del menú. Cada perfil la tiene en nivel C (consulta) o M (modificación). */
class Acceso extends Model
{
    protected $table = 'accesos';
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'orden'];

    public const CONSULTA = 'C';
    public const MODIFICACION = 'M';
    public const NIVELES = [self::CONSULTA => 'Solo consulta', self::MODIFICACION => 'Modificación'];

    /** Accesos que usa el código (rutas y menú): no se pueden borrar ni cambiar de código. */
    public const DEL_SISTEMA = ['cotizaciones', 'clientes', 'usuarios', 'perfiles', 'accesos'];

    public function perfiles(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'acceso_perfil')->withPivot('nivel');
    }

    public function esDelSistema(): bool
    {
        return in_array($this->codigo, self::DEL_SISTEMA, true);
    }

    public static function ordenados()
    {
        return static::orderBy('orden')->orderBy('nombre')->get();
    }
}
