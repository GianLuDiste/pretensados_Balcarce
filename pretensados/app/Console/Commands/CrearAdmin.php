<?php

namespace App\Console\Commands;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CrearAdmin extends Command
{
    protected $signature = 'usuarios:crear-admin {username? : Nombre de usuario}';
    protected $description = 'Crea (o repone) un usuario con el perfil Administrador (acceso total)';

    public function handle(): int
    {
        $username = $this->argument('username') ?: $this->ask('Nombre de usuario');
        $nombre   = $this->ask('Nombre completo');
        $email    = $this->ask('Email');
        $clave    = $this->secret('Contraseña (mínimo 8 caracteres)');
        $repite   = $this->secret('Repetí la contraseña');

        $v = Validator::make(
            compact('username', 'nombre', 'email', 'clave') + ['clave_confirmation' => $repite],
            [
                'username' => ['required', 'alpha_dash:ascii', 'max:50'],
                'nombre'   => ['required', 'max:100'],
                'email'    => ['required', 'email', 'max:100'],
                'clave'    => ['required', 'confirmed', Password::min(8)],
            ]
        );
        if ($v->fails()) {
            foreach ($v->errors()->all() as $e) $this->error($e);
            return self::FAILURE;
        }

        $user = User::updateOrCreate(['username' => $username], [
            'name' => $nombre, 'email' => $email, 'password' => $clave, 'activo' => true,
        ]);

        // Perfil Administrador del sistema (acceso total); se recrea si alguien lo hubiera borrado de la base.
        $admin = Perfil::where('es_admin', true)->first()
            ?? tap(new Perfil(['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema.']), function ($p) {
                $p->es_admin = true;
                $p->save();
            });
        $user->perfiles()->syncWithoutDetaching([$admin->id]);

        $this->info("Administrador '{$username}' listo.");
        return self::SUCCESS;
    }
}
