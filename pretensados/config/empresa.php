<?php

/*
 * Datos de la empresa para las impresiones (encabezado y firma).
 * Valores tomados del reporte Crystal legacy/Cotizacion.rpt; se pueden pisar desde el .env.
 */
return [
    'razon_social' => env('EMPRESA_RAZON_SOCIAL', 'Pretensados Balcarce S.A.'),
    'direccion'    => env('EMPRESA_DIRECCION', 'Ruta 55 - Km 77,5 - (7620) Balcarce - Bs. As.'),
    'telefono'     => env('EMPRESA_TELEFONO', '(02266) 42-2166'),
    'email'        => env('EMPRESA_EMAIL', 'pretensadosbalcarce@telefax.com.ar'),
    'iva'          => env('EMPRESA_IVA', 'IVA Responsable Inscripto'),

    // Firma al pie de la cotización
    'firma_nombre' => env('EMPRESA_FIRMA_NOMBRE', 'Eduardo G. Rodríguez'),
    'firma_cargo'  => env('EMPRESA_FIRMA_CARGO', 'Socio Gerente'),
];
