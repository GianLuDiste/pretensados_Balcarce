<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Seguridad por accesos y perfiles:
 *   accesos        una fila por opción del menú (unidad de función)
 *   perfiles       agrupan accesos; "Administrador" (es_admin) tiene acceso total
 *   acceso_perfil  nivel de cada perfil en cada acceso: C = solo consulta, M = modificación (incluye consulta)
 *   perfil_user    perfiles de cada usuario (sus accesos son la suma de los de sus perfiles)
 * Reemplaza las columnas users.es_admin y users.permisos (letras ABMCI del VB).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accesos', function (Blueprint $t) {
            $t->id();
            $t->string('codigo', 50)->unique();          // lo usa el código: middleware('acceso:clientes')
            $t->string('nombre', 100);
            $t->string('descripcion', 255)->nullable();
            $t->unsignedSmallInteger('orden')->default(0);
            $t->timestamps();
        });

        Schema::create('perfiles', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 100)->unique();
            $t->string('descripcion', 255)->nullable();
            $t->boolean('es_admin')->default(false);      // solo el perfil Administrador del sistema
            $t->timestamps();
        });

        Schema::create('acceso_perfil', function (Blueprint $t) {
            $t->foreignId('perfil_id')->constrained('perfiles')->cascadeOnDelete();
            $t->foreignId('acceso_id')->constrained('accesos')->cascadeOnDelete();
            $t->char('nivel', 1);                         // C | M
            $t->primary(['perfil_id', 'acceso_id']);
        });

        Schema::create('perfil_user', function (Blueprint $t) {
            $t->foreignId('perfil_id')->constrained('perfiles')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->primary(['perfil_id', 'user_id']);
        });

        $ahora = now();
        $accesos = [
            ['cotizaciones', 'Cotizaciones', 'Consulta, alta, versiones, modificación, baja e impresión de cotizaciones.', 10],
            ['clientes',     'Clientes',     'Consulta, alta, modificación, baja e impresión de clientes.',                 20],
            ['usuarios',     'Usuarios',     'ABM de usuarios y asignación de perfiles.',                                    900],
            ['perfiles',     'Perfiles',     'ABM de perfiles, sus accesos y sus usuarios.',                                 910],
            ['accesos',      'Accesos',      'ABM de los accesos (opciones del menú).',                                      920],
        ];
        foreach ($accesos as [$codigo, $nombre, $desc, $orden]) {
            DB::table('accesos')->insert(['codigo' => $codigo, 'nombre' => $nombre, 'descripcion' => $desc, 'orden' => $orden,
                'created_at' => $ahora, 'updated_at' => $ahora]);
        }
        $idAcceso = DB::table('accesos')->pluck('id', 'codigo');

        $nuevoPerfil = fn ($nombre, $desc, $admin = false) => DB::table('perfiles')->insertGetId([
            'nombre' => $nombre, 'descripcion' => $desc, 'es_admin' => $admin, 'created_at' => $ahora, 'updated_at' => $ahora]);

        $admin    = $nuevoPerfil('Administrador', 'Acceso total al sistema.', true);
        $operador = $nuevoPerfil('Operador comercial', 'Modificación de cotizaciones y clientes.');
        $consulta = $nuevoPerfil('Consulta comercial', 'Solo consulta de cotizaciones y clientes.');
        foreach (['cotizaciones', 'clientes'] as $c) {
            DB::table('acceso_perfil')->insert([
                ['perfil_id' => $operador, 'acceso_id' => $idAcceso[$c], 'nivel' => 'M'],
                ['perfil_id' => $consulta, 'acceso_id' => $idAcceso[$c], 'nivel' => 'C'],
            ]);
        }

        // Usuarios existentes: administradores → Administrador; el resto según sus letras del VB.
        foreach (DB::table('users')->get(['id', 'es_admin', 'permisos']) as $u) {
            $perfil = $u->es_admin ? $admin
                : (preg_match('/[ABM]/', (string) $u->permisos) ? $operador : $consulta);
            DB::table('perfil_user')->insert(['perfil_id' => $perfil, 'user_id' => $u->id]);
        }

        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['es_admin', 'permisos']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('es_admin')->default(false);
            $t->string('permisos', 10)->default('C');
        });

        $admins = DB::table('perfil_user')->join('perfiles', 'perfiles.id', '=', 'perfil_user.perfil_id')
            ->where('perfiles.es_admin', true)->pluck('perfil_user.user_id');
        DB::table('users')->whereIn('id', $admins)->update(['es_admin' => true, 'permisos' => 'ABMCI']);

        Schema::dropIfExists('perfil_user');
        Schema::dropIfExists('acceso_perfil');
        Schema::dropIfExists('perfiles');
        Schema::dropIfExists('accesos');
    }
};
