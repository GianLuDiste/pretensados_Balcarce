<?php
// NO reemplaces tu bootstrap/app.php: dentro de ->withMiddleware(...) agregá estas líneas.
// (Laravel 11/12)

->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'permiso' => \App\Http\Middleware\VerificarPermiso::class,
        'admin'   => \App\Http\Middleware\SoloAdmin::class,
    ]);
    $middleware->redirectGuestsTo('/login');        // quien no inició sesión va al login
    $middleware->redirectUsersTo('/cotizaciones');  // quien ya ingresó y abre /login va a la app
})
