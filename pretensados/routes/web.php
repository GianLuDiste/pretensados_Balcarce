<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
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

    // Permisos como en el VB: C=Consulta, A=Alta, M=Modificación, B=Baja, I=Impresión
    Route::prefix('cotizaciones')->name('cotizaciones.')
        ->where(['nro' => '[0-9]+', 'ver' => '[0-9]+'])
        ->middleware('permiso:C')
        ->group(function () {
            Route::get('/',                          [CotizacionController::class, 'index'])->name('index');
            Route::get('/nueva',                     [CotizacionController::class, 'create'])->name('create')->middleware('permiso:A');
            Route::post('/',                         [CotizacionController::class, 'store'])->name('store')->middleware('permiso:A');
            Route::get('/{nro}/{ver}',               [CotizacionController::class, 'edit'])->name('edit');
            Route::put('/{nro}/{ver}',               [CotizacionController::class, 'update'])->name('update')->middleware('permiso:M');
            Route::delete('/{nro}/{ver}',            [CotizacionController::class, 'destroy'])->name('destroy')->middleware('permiso:B');
            Route::get('/{nro}/{ver}/nueva-version', [CotizacionController::class, 'newVersion'])->name('newVersion')->middleware('permiso:A');
            Route::get('/{nro}/{ver}/imprimir',      [CotizacionController::class, 'imprimir'])->name('print')->middleware('permiso:I');
        });

    // ---------- Clientes (Clientes.frm) ----------
    Route::prefix('clientes')->name('clientes.')
        ->whereNumber('cliente')
        ->middleware('permiso:C')
        ->group(function () {
            Route::get('/',            [ClienteController::class, 'index'])->name('index');
            Route::get('/nuevo',       [ClienteController::class, 'create'])->name('create')->middleware('permiso:A');
            Route::post('/',           [ClienteController::class, 'store'])->name('store')->middleware('permiso:A');
            Route::get('/imprimir',    [ClienteController::class, 'imprimir'])->name('print')->middleware('permiso:I');
            Route::get('/{cliente}',   [ClienteController::class, 'edit'])->name('edit');
            Route::put('/{cliente}',   [ClienteController::class, 'update'])->name('update')->middleware('permiso:M');
            Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('destroy')->middleware('permiso:B');
        });

    Route::get('/articulos/{articulo}/precio', [CotizacionController::class, 'precioArticulo'])
        ->whereNumber('articulo')->name('articulos.precio')->middleware('permiso:C');

    // ---------- ABM de usuarios (solo administradores) ----------
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios', UserController::class)
            ->parameters(['usuarios' => 'usuario'])
            ->except('show');
    });
});
