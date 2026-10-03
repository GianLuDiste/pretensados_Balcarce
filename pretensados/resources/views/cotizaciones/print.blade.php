<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Cotización {{ $c->Nro_Cotizacion }}/{{ $c->Version }}</title>
    <style>
        body { font:13px/1.4 Arial, sans-serif; color:#000; margin:1.5cm; }
        header { display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #000; padding-bottom:.5rem; margin-bottom:1rem; }
        header img { max-height:70px; }
        h1 { font-size:18px; margin:0; }
        .dos { display:grid; grid-template-columns:1fr 1fr; gap:.2rem 2rem; margin-bottom:1rem; }
        table { width:100%; border-collapse:collapse; margin:.5rem 0 1rem; }
        th, td { border:1px solid #444; padding:3px 6px; }
        th { background:#eee; }
        .r { text-align:right; }
        .tot { width:45%; margin-left:auto; }
        .noprint { margin-bottom:1rem; }
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
    $n = fn ($x, $d = 2) => number_format((float) $x, $d, ',', '.');
@endphp
<p class="noprint"><button onclick="window.print()">Imprimir</button></p>
<header>
    <div>
        <strong>{{ $empresa->Razon_Social ?? '' }}</strong><br>{{ $empresa->Direccion ?? '' }}
    </div>
    @if ($logo) <img src="{{ $logo }}" alt="Logo"> @endif
</header>

<h1>Cotización Nº {{ $c->Nro_Cotizacion }}/{{ $c->Version }}</h1>
<p>Fecha: {{ $c->Fecha?->format('d/m/Y') }}</p>

<div class="dos">
    <div><strong>Cliente:</strong> {{ $c->cliente?->razon_social }}</div>
    <div><strong>CUIT:</strong> {{ $c->cliente?->CUIT }}</div>
    <div><strong>Dirección:</strong> {{ $c->cliente?->DIRECCION }}</div>
    <div><strong>Localidad:</strong> {{ $c->cliente?->ciudad?->DESCRIPCION }} ({{ $c->cliente?->cod_postal }})</div>
    <div><strong>Tel. / Fax:</strong> {{ trim($c->cliente?->TELEFONO . ($c->cliente?->FAX ? ' / ' . $c->cliente->FAX : '')) }}</div>
    <div><strong>e-Mail:</strong> {{ $c->cliente?->email }}</div>
    <div><strong>Contacto:</strong> {{ $c->Contacto }}</div>
</div>

<table>
    <thead><tr>
        <th>#</th><th>Artículo</th><th class="r">Cant.</th><th class="r">Kgs. unit.</th><th class="r">Kgs. total</th>
        <th class="r">Pcio. unit.</th><th class="r">Pcio. total</th>@if ($rentab)<th class="r">% Rent.</th>@endif
    </tr></thead>
    <tbody>
    @foreach ($items as $i => $it)
        <tr>
            <td>{{ $i + 1 }}</td><td>{{ $it->detalleArticulo?->IDENTIFICACION }}</td>
            <td class="r">{{ $it->Cantidad }}</td><td class="r">{{ $n($it->Kgs_unit, 3) }}</td><td class="r">{{ $n($it->Kgs_total, 3) }}</td>
            <td class="r">{{ $n($it->Pcio_unit) }}</td><td class="r">{{ $n($it->Pcio_total) }}</td>
            @if ($rentab)<td class="r">{{ $n($it->Porc_recargo) }}</td>@endif
        </tr>
    @endforeach
    </tbody>
</table>

<table class="tot">
    <tr><td>Kgs. total</td><td class="r">{{ $n($c->Total_kgs, 3) }}</td></tr>
    <tr><td>Subtotal</td><td class="r">{{ $n($c->Importe) }}</td></tr>
    <tr><td>IVA {{ $n($c->Porc_IVA) }}%</td><td class="r">{{ $n($c->Imp_IVA) }}</td></tr>
    @if ($c->Porc_IIBB > 0)<tr><td>IIBB {{ $n($c->Porc_IIBB) }}%</td><td class="r">{{ $n($c->Imp_IIBB) }}</td></tr>@endif
    <tr><th>TOTAL</th><th class="r">{{ $n($c->Imp_Total) }}</th></tr>
</table>

<p><strong>Plazo de entrega:</strong> {{ $c->plazo?->DESCRIPCION }}<br>
<strong>Lugar de entrega:</strong> {{ $c->Lugar_Entrega }}<br>
<strong>Flete:</strong> {{ $c->Flete }}<br>
<strong>Forma de pago:</strong> {{ $c->formaPago?->DESCRIPCION }} {{ $c->Obs_FPago }}<br>
<strong>Vigencia de la oferta:</strong> {{ $c->vigencia?->DESCRIPCION }}<br>
@if ($c->Observaciones)<strong>Observaciones:</strong> {{ $c->Observaciones }}@endif</p>
</body>
</html>
