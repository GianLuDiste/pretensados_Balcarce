<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de clientes</title>
    <style>
        body { font:11px/1.35 Arial, sans-serif; color:#000; margin:1.2cm; }
        header { display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #000; padding-bottom:.5rem; margin-bottom:1rem; }
        header img { max-height:60px; }
        h1 { font-size:16px; margin:0 0 .3rem; }
        table { width:100%; border-collapse:collapse; }
        th, td { border:1px solid #444; padding:2px 5px; vertical-align:top; }
        th { background:#eee; text-align:left; }
        thead { display:table-header-group; }
        tr { page-break-inside:avoid; }
        .r { text-align:right; }
        .noprint { margin-bottom:1rem; }
        @page { size: A4 landscape; }
        @media print { .noprint { display:none; } body { margin:0; } }
    </style>
</head>
<body>
@php
    $logo = null;
    if (!empty($empresa?->Logo)) {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($empresa->Logo) ?: 'image/jpeg';
        $logo = 'data:' . $mime . ';base64,' . base64_encode($empresa->Logo);
    }
@endphp
<p class="noprint"><button onclick="window.print()">Imprimir</button></p>
<header>
    <div>
        <strong>{{ $empresa->Razon_Social ?? '' }}</strong><br>{{ $empresa->Direccion ?? '' }}
    </div>
    @if ($logo) <img src="{{ $logo }}" alt="Logo"> @endif
</header>

<h1>Listado de clientes</h1>
<p>Fecha: {{ now()->format('d/m/Y') }} · {{ $clientes->count() }} clientes @if ($filtro) · Filtro: {{ $filtro }} @endif</p>

<table>
    <thead><tr>
        <th class="r">Código</th><th>Razón social</th><th>Dirección</th><th>Localidad</th><th class="r">CP</th>
        <th>Teléfono / Fax</th><th>CUIT</th><th>IVA</th><th>Contacto</th><th>E-mail</th>
    </tr></thead>
    <tbody>
    @foreach ($clientes as $c)
        <tr>
            <td class="r">{{ $c->CODIGO }}</td>
            <td>{{ $c->razon_social }}</td>
            <td>{{ $c->DIRECCION }}</td>
            <td>{{ $c->ciudad?->DESCRIPCION }}</td>
            <td class="r">{{ $c->cod_postal ?: '' }}</td>
            <td>{{ trim($c->TELEFONO . ($c->FAX ? ' / ' . $c->FAX : '')) }}</td>
            <td>{{ $c->CUIT }}</td>
            <td>{{ $c->condicionIva?->DESCRIPCION }}</td>
            <td>{{ $c->CONTACTO }}</td>
            <td>{{ $c->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
