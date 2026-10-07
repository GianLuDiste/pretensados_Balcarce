<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Cotización {{ $c->Nro_Cotizacion }}/{{ $c->Version }} · {{ $c->cliente?->razon_social }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    {{-- Formato tomado del reporte Crystal legacy/Cotizacion.rpt --}}
    <style>
        @page { size: A4 portrait; margin: 1.3cm 1.4cm; }
        * { box-sizing: border-box; }
        body { font: 11.5px/1.4 Calibri, Carlito, "Segoe UI", Arial, sans-serif; color: #111; margin: 1.3cm 1.4cm; }
        .azul { color: #28324D; }
        h2 { font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #28324D;
             border-bottom: 1px solid #28324D; padding-bottom: 2px; margin: 1.1rem 0 .45rem; }

        /* Cliente */
        .cliente { display: grid; grid-template-columns: 1fr 1fr; gap: .15rem 2rem; border: 1px solid #b9bfcc; border-radius: 4px; padding: .55rem .75rem; }
        .cliente .full { grid-column: 1 / -1; }
        .et { color: #555; display: inline-block; min-width: 5.6rem; }

        /* Ítems */
        table.items { width: 100%; border-collapse: collapse; margin-top: .9rem; }
        .items th { background: #28324D; color: #fff; font-weight: 600; padding: 4px 6px; text-align: left; font-size: 10.5px; }
        .items td { padding: 4px 6px; border-bottom: 1px solid #dde0e7; vertical-align: top; }
        .items tbody tr:nth-child(even) td { background: #f5f6f9; }
        .r, .items th.r { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
        .c, .items th.c { text-align: center; }

        /* Totales */
        .pie { display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem; margin-top: .6rem; }
        .son { padding-top: .3rem; }
        table.tot { border-collapse: collapse; min-width: 46%; }
        .tot td { padding: 3px 6px; }
        .tot td:first-child { color: #333; }
        .tot .total td { border-top: 2px solid #28324D; font-weight: 700; font-size: 1.15em; color: #28324D; padding-top: 5px; }

        /* Condiciones y firma */
        .cond { display: grid; grid-template-columns: max-content 1fr; gap: .2rem 1rem; }
        .cond dt { color: #555; }
        .cond dd { margin: 0; }
        .firma { margin-top: 2.6rem; display: flex; justify-content: flex-end; }
        .firma div { text-align: center; min-width: 15rem; border-top: 1px solid #333; padding-top: .3rem; font-family: "Segoe UI", Calibri, Arial, sans-serif; }

        .noprint { margin-bottom: 1rem; font: 14px system-ui, sans-serif; }
        .noprint button { font: inherit; padding: .4rem .9rem; cursor: pointer; }
        @media print {
            .noprint { display: none; }
            body { margin: 0; }
            .items th, .items tbody tr:nth-child(even) td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .items tr, .pie, .cond, .firma { break-inside: avoid; }
        }
    </style>
</head>
<body>
@php
    $n = fn ($x, $d = 2) => number_format((float) $x, $d, ',', '.');
    $cl = $c->cliente;
    $telFax = collect([$cl?->TELEFONO, $cl?->FAX])->map(fn ($t) => trim((string) $t))->filter()->implode(' / ');
    $localidad = trim(($cl?->ciudad?->DESCRIPCION ?? '') . ($cl?->cod_postal ? ' (' . $cl->cod_postal . ')' : ''));
    $articulos = $items->sum('Cantidad');
@endphp

<p class="noprint">
    <button onclick="window.print()">Imprimir / Guardar PDF</button>
    {{-- Se abre en otra pestaña: "Cerrar" la cierra y deja la consulta como estaba; si no se puede cerrar, vuelve a la consulta filtrada. --}}
    <button onclick="window.close(); setTimeout(() => location.href = @js(\App\Http\Controllers\CotizacionController::urlConsulta()), 150)">Cerrar</button>
</p>

@include('layouts._print-header', [
    'titulo'    => "COTIZACIÓN Nº {$c->Nro_Cotizacion}" . ($c->Version ? "/{$c->Version}" : ''),
    'subtitulo' => 'Fecha: ' . $c->Fecha?->format('d/m/Y'),
])

<section class="cliente">
    <div class="full"><span class="et">Señor/es:</span> <strong>{{ $cl?->razon_social }}</strong></div>
    <div><span class="et">Dirección:</span> {{ $cl?->DIRECCION }}</div>
    <div><span class="et">Localidad:</span> {{ $localidad }}</div>
    <div><span class="et">Tel./Fax:</span> {{ $telFax }}</div>
    <div><span class="et">E-Mail:</span> {{ $cl?->email }}</div>
    <div><span class="et">CUIT:</span> {{ $cl?->CUIT }}</div>
    <div><span class="et">Contacto:</span> {{ $c->Contacto }}</div>
</section>

<table class="items">
    <thead><tr>
        <th class="c" style="width:2.6rem">Item</th>
        <th>Descripción del Material</th>
        <th class="r">Cantidad</th>
        <th class="r">Kgs/unit</th>
        <th class="r">Kgs/total</th>
        <th class="r">Imp. Unit.</th>
        <th class="r">Importe Total</th>
        @if ($rentab)<th class="r">% Rent.</th>@endif
    </tr></thead>
    <tbody>
    @foreach ($items as $i => $it)
        <tr>
            <td class="c">{{ $i + 1 }}</td>
            <td>{{ $it->detalleArticulo?->DESCRIPCION ?: $it->detalleArticulo?->IDENTIFICACION }}</td>
            <td class="r">{{ $n($it->Cantidad, 0) }}</td>
            <td class="r">{{ $n($it->Kgs_unit) }}</td>
            <td class="r">{{ $n($it->Kgs_total) }}</td>
            <td class="r">$ {{ $n($it->Pcio_unit) }}</td>
            <td class="r">$ {{ $n($it->Pcio_total) }}</td>
            @if ($rentab)<td class="r">{{ $n($it->Porc_recargo) }}</td>@endif
        </tr>
    @endforeach
    </tbody>
</table>

<div class="pie">
    <div class="son">Son <strong>{{ $n($articulos, 0) }}</strong> {{ $articulos == 1 ? 'artículo' : 'artículos' }}.</div>
    <table class="tot">
        <tr><td>Kgs.</td><td class="r">{{ $n($c->Total_kgs) }}</td></tr>
        <tr><td>SUBTOTAL</td><td class="r">$ {{ $n($c->Importe) }}</td></tr>
        <tr><td>IVA ({{ $n($c->Porc_IVA) }} %)</td><td class="r">$ {{ $n($c->Imp_IVA) }}</td></tr>
        @if ($c->Porc_IIBB > 0)
            <tr><td>Percep. IIBB ({{ $n($c->Porc_IIBB) }} %)</td><td class="r">$ {{ $n($c->Imp_IIBB) }}</td></tr>
        @endif
        <tr class="total"><td>TOTAL</td><td class="r">$ {{ $n($c->Imp_Total) }}</td></tr>
    </table>
</div>

<h2>Condiciones generales</h2>
<dl class="cond">
    <dt>Plazo de entrega:</dt>     <dd>{{ $c->plazo?->DESCRIPCION }}</dd>
    <dt>Lugar de entrega:</dt>     <dd>{{ $c->Lugar_Entrega }}</dd>
    <dt>Flete:</dt>                <dd>{{ $c->Flete }}</dd>
    @if ($c->Observaciones)
        <dt>Observaciones:</dt>    <dd>{{ $c->Observaciones }}</dd>
    @endif
    <dt>Forma de pago:</dt>        <dd>{{ $c->formaPago?->DESCRIPCION }}@if ($c->Obs_FPago) — {{ $c->Obs_FPago }}@endif</dd>
    <dt>Validez de la oferta:</dt> <dd>{{ $c->vigencia?->DESCRIPCION }}</dd>
</dl>

<div class="firma">
    <div>
        <strong>{{ config('empresa.firma_nombre') }}</strong><br>
        {{ config('empresa.firma_cargo') }}<br>
        {{ config('empresa.razon_social') }}
    </div>
</div>
</body>
</html>
