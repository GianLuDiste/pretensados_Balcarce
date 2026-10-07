<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'username', 'email', 'telefono', 'password', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',   // se encripta sola con bcrypt al asignarla
        'activo'   => 'boolean',
    ];

    /** [codigo => 'C'|'M'] calculado una sola vez por request; null = aún no calculado. */
    private ?array $nivelesCache = null;
    private ?bool $esAdminCache = null;

    public function perfiles(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'perfil_user');
    }

    /** Tiene el perfil Administrador: acceso total. */
    public function esAdmin(): bool
    {
        return $this->esAdminCache ??= $this->perfiles()->where('es_admin', true)->exists();
    }

    /**
     * Nivel efectivo en un acceso: la suma de sus perfiles (M gana a C).
     * Devuelve 'M', 'C' o null si no tiene acceso.
     */
    public function nivelEn(string $codigo): ?string
    {
        if ($this->esAdmin()) {
            return Acceso::MODIFICACION;
        }

        $this->nivelesCache ??= DB::table('perfil_user')
            ->join('acceso_perfil', 'acceso_perfil.perfil_id', '=', 'perfil_user.perfil_id')
            ->join('accesos', 'accesos.id', '=', 'acceso_perfil.acceso_id')
            ->where('perfil_user.user_id', $this->id)
            ->groupBy('accesos.codigo')
            ->selectRaw('accesos.codigo, MAX(acceso_perfil.nivel) AS nivel')   // 'M' > 'C'
            ->pluck('nivel', 'codigo')->all();

        return $this->nivelesCache[$codigo] ?? null;
    }

    public function puedeVer(string $codigo): bool
    {
        return $this->nivelEn($codigo) !== null;
    }

    public function puedeModificar(string $codigo): bool
    {
        return $this->nivelEn($codigo) === Acceso::MODIFICACION;
    }

    /** ¿Es el único administrador activo que queda? */
    public function esUltimoAdminActivo(): bool
    {
        return $this->esAdmin() && $this->activo && ! static::adminsActivos()->where('users.id', '!=', $this->id)->exists();
    }

    public static function adminsActivos()
    {
        return static::where('activo', true)->whereHas('perfiles', fn ($q) => $q->where('es_admin', true));
    }
}
