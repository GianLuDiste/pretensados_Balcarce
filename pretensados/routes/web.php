<?php

use App\Http\Controllers\AccesoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ---------- Acceso (solo para quien NO inició sesión) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/olvide-mi-clave',          [PasswordResetController::class, 'formOlvide'])->name('password.request');
    Route::post('/olvide-mi-clave',         [PasswordResetController::class, 'enviarEnlace'])->name('password.email');
    Route::get('/restablecer-clave/{token}', [PasswordResetController::class, 'formRestablecer'])->name('password.reset');
    Route::post('/restablecer-clave',       [PasswordResetController::class, 'restablecer'])->name('password.update');
});

// ---------- Todo lo demás exige sesión iniciada ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::view('/', 'home')->name('home');   // pantalla principal tras el login

    // Seguridad: cada opción del menú es un acceso (tabla accesos).
    //   acceso:codigo     → consulta: ver, buscar e imprimir
    //   acceso:codigo,M   → modificación: alta, cambios y baja
    Route::prefix('cotizaciones')->name('cotizaciones.')
        ->where(['nro' => '[0-9]+', 'ver' => '[0-9]+'])
        ->middleware('acceso:cotizaciones')
        ->group(function () {
            Route::get('/',                          [CotizacionController::class, 'index'])->name('index');
            Route::get('/nueva',                     [CotizacionController::class, 'create'])->name('create')->middleware('acceso:cotizaciones,M');
            Route::post('/',                         [CotizacionController::class, 'store'])->name('store')->middleware('acceso:cotizaciones,M');
            Route::get('/{nro}/{ver}',               [CotizacionController::class, 'edit'])->name('edit');
            Route::put('/{nro}/{ver}',               [CotizacionController::class, 'update'])->name('update')->middleware('acceso:cotizaciones,M');
            Route::delete('/{nro}/{ver}',            [CotizacionController::class, 'destroy'])->name('destroy')->middleware('acceso:cotizaciones,M');
            Route::get('/{nro}/{ver}/nueva-version', [CotizacionController::class, 'newVersion'])->name('newVersion')->middleware('acceso:cotizaciones,M');
            Route::get('/{nro}/{ver}/imprimir',      [CotizacionController::class, 'imprimir'])->name('print');
        });

    Route::get('/articulos/{articulo}/precio', [CotizacionController::class, 'precioArticulo'])
        ->whereNumber('articulo')->name('articulos.precio')->middleware('acceso:cotizaciones');

    // ---------- Clientes (Clientes.frm) ----------
    Route::prefix('clientes')->name('clientes.')
        ->whereNumber('cliente')
        ->middleware('acceso:clientes')
        ->group(function () {
            Route::get('/',             [ClienteController::class, 'index'])->name('index');
            Route::get('/nuevo',        [ClienteController::class, 'create'])->name('create')->middleware('acceso:clientes,M');
            Route::post('/',            [ClienteController::class, 'store'])->name('store')->middleware('acceso:clientes,M');
            Route::get('/imprimir',     [ClienteController::class, 'imprimir'])->name('print');
            Route::get('/{cliente}',    [ClienteController::class, 'edit'])->name('edit');
            Route::put('/{cliente}',    [ClienteController::class, 'update'])->name('update')->middleware('acceso:clientes,M');
            Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('destroy')->middleware('acceso:clientes,M');
        });

    // ---------- Seguridad: usuarios, perfiles y accesos ----------
    $abm = function (string $prefijo, string $param, string $controller) {
        Route::prefix($prefijo)->name("{$prefijo}.")
            ->whereNumber($param)
            ->middleware("acceso:{$prefijo}")
            ->group(function () use ($prefijo, $param, $controller) {
                Route::get('/',              [$controller, 'index'])->name('index');
                Route::get('/nuevo',         [$controller, 'create'])->name('create')->middleware("acceso:{$prefijo},M");
                Route::post('/',             [$controller, 'store'])->name('store')->middleware("acceso:{$prefijo},M");
                Route::get("/{{$param}}",    [$controller, 'edit'])->name('edit');
                Route::put("/{{$param}}",    [$controller, 'update'])->name('update')->middleware("acceso:{$prefijo},M");
                Route::delete("/{{$param}}", [$controller, 'destroy'])->name('destroy')->middleware("acceso:{$prefijo},M");
            });
    };
    $abm('usuarios', 'usuario', UserController::class);
    $abm('perfiles', 'perfil',  PerfilController::class);
    $abm('accesos',  'acceso',  AccesoController::class);
});
