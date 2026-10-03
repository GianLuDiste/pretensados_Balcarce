<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crea (o completa, si Laravel ya la creó) la tabla `users` y la de tokens de
 * recuperación de contraseña. NO toca ninguna de las tablas del sistema viejo.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $t) {
                $t->id();
                $t->string('name', 100);                    // nombre completo
                $t->string('username', 50)->unique();
                $t->string('email', 100)->unique();
                $t->string('telefono', 30)->nullable();
                $t->string('password');
                $t->boolean('es_admin')->default(false);
                $t->string('permisos', 10)->default('C');   // letras del VB: A B M C I
                $t->boolean('activo')->default(true);
                $t->rememberToken();
                $t->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $t) {
                if (! Schema::hasColumn('users', 'username'))  $t->string('username', 50)->nullable()->unique()->after('name');
                if (! Schema::hasColumn('users', 'telefono'))  $t->string('telefono', 30)->nullable();
                if (! Schema::hasColumn('users', 'es_admin'))  $t->boolean('es_admin')->default(false);
                if (! Schema::hasColumn('users', 'permisos'))  $t->string('permisos', 10)->default('C');
                if (! Schema::hasColumn('users', 'activo'))    $t->boolean('activo')->default(true);
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $t) {
                $t->string('email')->primary();
                $t->string('token');
                $t->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Intencionalmente vacío: no se borran usuarios al revertir.
    }
};
