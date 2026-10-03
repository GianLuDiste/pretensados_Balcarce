@extends('layouts.app')
@section('title', 'Cotizaciones')
@section('content')
<h1>Cotizaciones</h1>

<section class="panel">
    <form method="GET" class="grid" style="align-items:end">
        <div><label for="nro">Nº de cotización</label><input id="nro" type="number" name="nro" value="{{ request('nro') }}"></div>
        <div><label for="cliente">Cliente</label><input id="cliente" name="cliente" value="{{ request('cliente') }}"></div>
        <div class="actions"><button class="btn">Buscar</button><a class="btn sec" href="{{ route('cotizaciones.index') }}">Limpiar</a></div>
    </form>
</section>

<section class="panel scroll">
    <table>
        <thead><tr>
            <th>Nº / Ver.</th><th>Fecha</th><th>Cliente</th><th class="num">Kgs</th><th class="num">Importe</th><th class="num">Total</th><th>Estado</th><th></th>
        </tr></thead>
        <tbody>
        @forelse ($cotizaciones as $c)
            <tr>
                <td>{{ $c->Nro_Cotizacion }} / {{ $c->Version }}</td>
                <td>{{ $c->Fecha?->format('d/m/Y') }}</td>
                <td>{{ $c->cliente?->razon_social }}</td>
                <td class="num">{{ number_format($c->Total_kgs, 2, ',', '.') }}</td>
                <td class="num">{{ number_format($c->Importe, 2, ',', '.') }}</td>
                <td class="num">{{ number_format($c->Imp_Total, 2, ',', '.') }}</td>
                <td>{{ $c->IdStatus }}</td>
                <td class="actions">
                    <a href="{{ route('cotizaciones.edit', [$c->Nro_Cotizacion, $c->Version]) }}">Abrir</a>
                    @if (auth()->user()->puede('I'))
                        <a href="{{ route('cotizaciones.print', [$c->Nro_Cotizacion, $c->Version]) }}" target="_blank">Imprimir</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="info">No hay cotizaciones para esa búsqueda.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
{{ $cotizaciones->links('vendor.pagination.brand') }}
@endsection
