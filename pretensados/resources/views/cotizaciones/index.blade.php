@extends('layouts.app')
@section('title', 'Cotizaciones')
@section('main_class', 'page ancha')
@section('content')
@php
    $modifica = auth()->user()->puedeModificar('cotizaciones');
    $nombresSel = $estados->whereIn('idStatus', $filtro['estados'])->pluck('estado');
@endphp
<h1>Cotizaciones</h1>

<section class="panel">
    <form method="GET" class="filtros">
        <div class="f-nro"><label for="nro">Nº de cotización</label><input id="nro" type="number" name="nro" value="{{ request('nro') }}"></div>
        <div class="f-cliente"><label for="cliente">Cliente</label><input id="cliente" name="cliente" value="{{ request('cliente') }}"></div>
        <div class="f-fecha">
            <label for="desde">Fecha</label>
            <div class="rango">
                <input id="desde" type="date" name="desde" value="{{ $filtro['desde'] }}" aria-label="Desde">
                <span aria-hidden="true">a</span>
                <input id="hasta" type="date" name="hasta" value="{{ $filtro['hasta'] }}" aria-label="Hasta">
            </div>
        </div>
        <div class="f-estado">
            <label id="lbl-estados">Estado</label>
            <details class="multi" id="f-estados">
                <summary aria-labelledby="lbl-estados">
                    <span class="multi-txt">{{ $nombresSel->isEmpty() ? 'Todos' : $nombresSel->implode(', ') }}</span>
                </summary>
                <div class="multi-panel" role="group" aria-labelledby="lbl-estados">
                    <label class="check-line multi-todos"><input type="checkbox" data-todos @checked(empty($filtro['estados']))> Todos</label>
                    @foreach ($estados as $e)
                        <label class="check-line">
                            <input type="checkbox" name="estados[]" value="{{ $e->idStatus }}" @checked(in_array($e->idStatus, $filtro['estados'], true))>
                            <span class="badge {{ \App\Models\CotizacionEstado::clase($e->idStatus) }}">{{ $e->estado }}</span>
                        </label>
                    @endforeach
                </div>
            </details>
        </div>
        <div class="actions"><button class="btn">Buscar</button><a class="btn sec" href="{{ route('cotizaciones.index') }}">Limpiar</a></div>
    </form>
</section>

<section class="panel scroll">
    <table>
        <thead><tr>
            <th>Nº/Ver.</th><th>Fecha</th><th>Cliente</th><th class="num">Total</th><th>Estado</th>
            <th><span class="sr-only">Acciones</span></th>
        </tr></thead>
        <tbody>
        @forelse ($cotizaciones as $c)
            @php($ref = "{$c->Nro_Cotizacion}/{$c->Version}")
            <tr>
                <td>{{ $ref }}</td>
                <td>{{ $c->Fecha?->format('d/m/Y') }}</td>
                <td>{{ $c->cliente?->razon_social }}</td>
                <td class="num">{{ number_format($c->Imp_Total, 2, ',', '.') }}</td>
                <td><span class="badge {{ \App\Models\CotizacionEstado::clase($c->IdStatus) }}" title="{{ $c->IdStatus }}">{{ $c->estado?->estado ?? $c->IdStatus }}</span></td>
                <td class="iconos">
                    <a class="ico-btn" href="{{ route('cotizaciones.edit', [$c->Nro_Cotizacion, $c->Version]) }}"
                       title="{{ $modifica ? 'Abrir / editar' : 'Ver' }} cotización {{ $ref }}" aria-label="{{ $modifica ? 'Abrir' : 'Ver' }} cotización {{ $ref }}">
                        @if ($modifica)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        @endif
                    </a>
                    <a class="ico-btn" href="{{ route('cotizaciones.print', [$c->Nro_Cotizacion, $c->Version]) }}" target="_blank"
                       title="Imprimir cotización {{ $ref }}" aria-label="Imprimir cotización {{ $ref }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="info">No hay cotizaciones para esa búsqueda.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
{{ $cotizaciones->links('vendor.pagination.brand') }}
@endsection

@push('scripts')
<script>
// Combo de estados con selección múltiple: "Todos" = sin filtro.
(function () {
    const box = document.getElementById('f-estados');
    const todos = box.querySelector('[data-todos]');
    const opciones = [...box.querySelectorAll('input[name="estados[]"]')];
    const txt = box.querySelector('.multi-txt');

    function refrescar() {
        const sel = opciones.filter(o => o.checked);
        todos.checked = sel.length === 0;
        txt.textContent = sel.length ? sel.map(o => o.closest('label').textContent.trim()).join(', ') : 'Todos';
    }
    todos.addEventListener('change', () => { opciones.forEach(o => { o.checked = false; }); refrescar(); });
    opciones.forEach(o => o.addEventListener('change', refrescar));

    // Cerrar al hacer clic afuera o con Escape
    document.addEventListener('click', e => { if (!box.contains(e.target)) box.open = false; });
    box.addEventListener('keydown', e => { if (e.key === 'Escape') { box.open = false; box.querySelector('summary').focus(); } });
})();
</script>
@endpush
