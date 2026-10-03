<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CrearAdmin extends Command
{
    protected $signature = 'usuarios:crear-admin {username? : Nombre de usuario}';
    protected $description = 'Crea (o repone) un usuario administrador con todos los permisos';

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

        User::updateOrCreate(['username' => $username], [
            'name' => $nombre, 'email' => $email, 'password' => $clave,
            'es_admin' => true, 'permisos' => 'ABMCI', 'activo' => true,
        ]);

        $this->info("Administrador '{$username}' listo.");
        return self::SUCCESS;
    }
}
