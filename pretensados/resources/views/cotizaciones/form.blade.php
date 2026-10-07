@extends('layouts.app')
@section('title', 'Cotización')

@php
    $items = old('items', $form['items']);
    $v = fn ($k) => old($k, $form[$k]);
    $action = $modo === 'editar' ? route('cotizaciones.update', [$nro, $ver]) : route('cotizaciones.store');
@endphp

@section('content')
<h1>
    @if ($modo === 'editar') Cotización {{ $nro }} / {{ $ver }}
    @elseif ($modo === 'version') Nueva versión de la cotización {{ $nro }} (se guardará como versión {{ $proximaVersion }})
    @else Nueva cotización @endif
</h1>

@if ($errors->any())
    <div class="alert err"><strong>Revisá estos datos:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form id="form-cotiz" method="POST" action="{{ $action }}">
    @csrf
    @if ($modo === 'editar') @method('PUT') @endif
    @if ($modo === 'version') <input type="hidden" name="nro_cotizacion" value="{{ $nro }}"> @endif

    <section class="panel">
        <div class="grid">
            <div><label for="fecha">Fecha</label><input id="fecha" type="date" name="fecha" value="{{ $v('fecha') }}" required></div>
            <div><label for="porc_recargo">% s/Costo (por defecto en los ítems)</label>
                <input id="porc_recargo" type="number" step="any" name="porc_recargo" value="{{ $v('porc_recargo') }}"></div>
        </div>
    </section>

    @php
        // Si hay errores en las condiciones, se abre esa pestaña para que se vean.
        $camposCond = ['id_plazo_entrega', 'lugar_entrega', 'flete', 'observaciones', 'id_forma_pago', 'obs_fpago', 'id_vigencia'];
        $tabCond = $errors->hasAny($camposCond) && ! $errors->hasAny(['id_cliente', 'contacto']);
    @endphp
    <section class="panel tabs" id="tabs-cotiz">
        <div class="tab-list" role="tablist" aria-label="Datos de la cotización">
            <button type="button" role="tab" id="tab-cliente" aria-controls="pane-cliente" aria-selected="{{ $tabCond ? 'false' : 'true' }}" tabindex="{{ $tabCond ? '-1' : '0' }}">Datos del cliente</button>
            <button type="button" role="tab" id="tab-cond" aria-controls="pane-cond" aria-selected="{{ $tabCond ? 'true' : 'false' }}" tabindex="{{ $tabCond ? '0' : '-1' }}">
                Condiciones generales @if ($errors->hasAny($camposCond))<span class="tab-alerta" title="Hay datos para revisar">!</span>@endif
            </button>
        </div>

        <div class="tab-pane" role="tabpanel" id="pane-cliente" aria-labelledby="tab-cliente" @if ($tabCond) hidden @endif>
        <div class="grid">
            <div class="span2"><label for="id_cliente">Cliente</label>
                <select id="id_cliente" name="id_cliente" required>
                    <option value="">Elegí un cliente</option>
                    @foreach ($clientes as $cl)
                        <option value="{{ $cl->CODIGO }}" @selected($v('id_cliente') == $cl->CODIGO)
                            data-dir="{{ $cl->DIRECCION }}"
                            data-tel="{{ collect([$cl->TELEFONO, $cl->FAX])->map(fn ($t) => trim((string) $t))->filter()->implode(' / ') }}"
                            data-loc="{{ $cl->ciudad?->DESCRIPCION }}"
                            data-mail="{{ $cl->email }}" data-cp="{{ $cl->cod_postal }}"
                            data-cuit="{{ $cl->CUIT }}" data-contacto="{{ $cl->CONTACTO }}">{{ $cl->razon_social }}</option>
                    @endforeach
                </select></div>
            <div><label>Dirección</label><input id="c-dir" readonly></div>
            <div><label>Tel. / Fax</label><input id="c-tel" readonly></div>
            <div><label>Localidad</label><input id="c-loc" readonly></div>
            <div><label>Cód. postal</label><input id="c-cp" readonly></div>
            <div><label>e-Mail</label><input id="c-mail" readonly></div>
            <div><label>CUIT</label><input id="c-cuit" readonly></div>
            <div><label for="contacto">Contacto</label><input id="contacto" name="contacto" maxlength="100" value="{{ $v('contacto') }}"></div>
        </div>
        </div>

        <div class="tab-pane" role="tabpanel" id="pane-cond" aria-labelledby="tab-cond" @unless ($tabCond) hidden @endunless>
        <div class="grid">
            <div><label for="id_plazo_entrega">Plazo de entrega</label>
                <select id="id_plazo_entrega" name="id_plazo_entrega" required>
                    <option value="">Elegí un plazo</option>
                    @foreach ($plazos as $p)<option value="{{ $p->IdPlazo }}" @selected($v('id_plazo_entrega') == $p->IdPlazo)>{{ $p->DESCRIPCION }}</option>@endforeach
                </select></div>
            <div><label for="lugar_entrega">Lugar de entrega</label><input id="lugar_entrega" name="lugar_entrega" maxlength="100" value="{{ $v('lugar_entrega') }}"></div>
            <div><label for="flete">Flete</label><input id="flete" name="flete" maxlength="100" value="{{ $v('flete') }}"></div>
            <div><label for="observaciones">Observaciones</label><input id="observaciones" name="observaciones" maxlength="100" value="{{ $v('observaciones') }}"></div>
            <div><label for="id_forma_pago">Forma de pago</label>
                <select id="id_forma_pago" name="id_forma_pago" required>
                    <option value="">Elegí una forma de pago</option>
                    @foreach ($formas as $f)<option value="{{ $f->CODIGO }}" @selected($v('id_forma_pago') == $f->CODIGO)>{{ $f->DESCRIPCION }}</option>@endforeach
                </select></div>
            <div><label for="obs_fpago">Observación de la forma de pago</label><input id="obs_fpago" name="obs_fpago" maxlength="50" value="{{ $v('obs_fpago') }}"></div>
            <div><label for="id_vigencia">Vigencia de la oferta</label>
                <select id="id_vigencia" name="id_vigencia" required>
                    <option value="">Elegí una vigencia</option>
                    @foreach ($vigencias as $vg)<option value="{{ $vg->IdVigencia }}" @selected($v('id_vigencia') == $vg->IdVigencia)>{{ $vg->DESCRIPCION }}</option>@endforeach
                </select></div>
        </div>
        </div>
    </section>

    <section class="panel">
        <h2>Ítems</h2>
        <div class="scroll">
        <table id="tabla-items">
            <thead><tr>
                <th>#</th><th style="min-width:260px">Artículo</th><th>Cant.</th><th>% s/costo</th>
                <th class="num">Kgs. unit.</th><th class="num">Kgs. total</th><th class="num">Pcio. unit.</th><th class="num">Pcio. total</th><th></th>
            </tr></thead>
            <tbody id="items-body">
            @foreach ($items as $i => $it)
                @include('cotizaciones._item_row', ['i' => $i, 'it' => $it, 'articulos' => $articulos])
            @endforeach
            </tbody>
        </table>
        </div>
        <p><button type="button" class="btn sec" id="btn-add">Agregar ítem</button></p>
    </section>

    <section class="panel totales-panel">
        <div class="totales-info">
            <div class="kgs"><span>Kgs. total</span> <output id="t-kgs">0</output></div>
            <p class="info">Los totales se recalculan en el servidor al guardar.</p>
        </div>
        <table class="totales" aria-label="Totales de la cotización">
            <tbody>
                <tr>
                    <th scope="row">Subtotal</th><td></td>
                    <td class="num"><output id="t-importe">0,00</output></td>
                </tr>
                <tr>
                    <th scope="row"><label for="porc_iva">% IVA</label></th>
                    <td><input id="porc_iva" type="number" step="any" min="0" name="porc_iva" value="{{ $v('porc_iva') }}" class="pct"></td>
                    <td class="num"><output id="t-iva">0,00</output></td>
                </tr>
                <tr>
                    <th scope="row"><label for="porc_iibb">% IIBB</label></th>
                    <td><input id="porc_iibb" type="number" step="any" min="0" name="porc_iibb" value="{{ $v('porc_iibb') }}" class="pct"></td>
                    <td class="num"><output id="t-iibb">0,00</output></td>
                </tr>
                <tr class="total">
                    <th scope="row">TOTAL</th><td></td>
                    <td class="num"><output id="t-total">0,00</output></td>
                </tr>
            </tbody>
        </table>
    </section>

    @php($modifica = auth()->user()->puedeModificar('cotizaciones'))
    <div class="actions">
        @if ($modifica)
            <button class="btn">Guardar</button>
        @endif
        <a class="btn sec" href="{{ $volver }}">{{ $modifica ? 'Cancelar' : 'Volver' }}</a>
        @if ($modo === 'editar')
            @if ($modifica) <a class="btn sec" href="{{ route('cotizaciones.newVersion', [$nro, $ver]) }}">Nueva versión</a> @endif
            <a class="btn sec" target="_blank" href="{{ route('cotizaciones.print', [$nro, $ver]) }}">Imprimir</a>
            <a class="btn sec" target="_blank" href="{{ route('cotizaciones.print', [$nro, $ver, 'rentab' => 1]) }}">Imprimir con % rentabilidad</a>
            @if ($modifica)
                <button class="btn danger" form="form-eliminar"
                    onclick="return confirm('¿Confirma la eliminación de la cotización {{ $nro }}/{{ $ver }}?')">Eliminar</button>
            @endif
        @endif
    </div>
</form>

@if ($modo === 'editar')
    <form id="form-eliminar" method="POST" action="{{ route('cotizaciones.destroy', [$nro, $ver]) }}">@csrf @method('DELETE')</form>
@endif

<template id="tpl-item">
    @include('cotizaciones._item_row', ['i' => '__I__', 'it' => ['articulo'=>null,'cantidad'=>1,'porc_recargo'=>null,'kgs_unit'=>null,'pcio_unit'=>null], 'articulos' => $articulos])
</template>
@endsection

@push('scripts')
<script>
const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];
const num = v => { const n = parseFloat(v); return isNaN(n) ? 0 : n; };
let nextIndex = {{ count($items) }};

// --- Datos del cliente (DatosClte) ---
function cargarCliente() {
    const o = $('#id_cliente').selectedOptions[0];
    const d = o ? o.dataset : {};
    $('#c-dir').value = d.dir || ''; $('#c-tel').value = d.tel || ''; $('#c-loc').value = d.loc || '';
    $('#c-cp').value = d.cp || '';   $('#c-mail').value = d.mail || ''; $('#c-cuit').value = d.cuit || '';
    return d;
}
$('#id_cliente').addEventListener('change', () => { $('#contacto').value = cargarCliente().contacto || ''; });
cargarCliente();

// Forma de pago 18 = "40% adelantado para mantener oferta": sin leyenda; el resto lleva la leyenda de variación.
$('#id_forma_pago').addEventListener('change', e => {
    $('#obs_fpago').value = e.target.value === '18' ? '' : 'PRECIO SUJETO A VARIACION';
});

// --- Ítems ---
function recalcFila(row) {
    const cant = num($('.i-cant', row).value);
    $('.i-kgs-total', row).textContent = (cant * num($('.i-kgs', row).value)).toFixed(3);
    $('.i-pcio-total', row).textContent = (cant * num($('.i-pcio', row).value)).toFixed(2);
    recalcTotales();
}

function recalcTotales() {
    let kgs = 0, imp = 0;
    $$('#items-body tr').forEach(r => {
        const cant = num($('.i-cant', r).value);
        kgs += cant * num($('.i-kgs', r).value);
        imp += Math.round(cant * num($('.i-pcio', r).value) * 100) / 100;
    });
    const iva = Math.round(imp * num($('#porc_iva').value)) / 100;
    const iibb = Math.round(imp * num($('#porc_iibb').value)) / 100;
    $('#t-kgs').value = fmt(kgs, 3);
    $('#t-importe').value = fmt(imp);
    $('#t-iva').value = fmt(iva);
    $('#t-iibb').value = fmt(iibb);
    $('#t-total').value = '$ ' + fmt(imp + iva + iibb);
}
function fmt(n, dec = 2) { return n.toLocaleString('es-AR', { minimumFractionDigits: dec, maximumFractionDigits: dec }); }

// --- Pestañas: Datos del cliente / Condiciones generales ---
(function () {
    const tabs = $$('#tabs-cotiz [role=tab]');
    function activar(tab, foco = false) {
        tabs.forEach(t => {
            const sel = t === tab;
            t.setAttribute('aria-selected', sel); t.tabIndex = sel ? 0 : -1;
            document.getElementById(t.getAttribute('aria-controls')).hidden = !sel;
        });
        if (foco) tab.focus();
    }
    tabs.forEach((t, i) => {
        t.addEventListener('click', () => activar(t));
        t.addEventListener('keydown', e => {      // flechas izquierda/derecha entre pestañas
            const d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
            if (d) { e.preventDefault(); activar(tabs[(i + d + tabs.length) % tabs.length], true); }
        });
    });
    // Un campo obligatorio vacío en la pestaña oculta: se muestra esa pestaña para que el navegador pueda señalarlo.
    $('#form-cotiz').addEventListener('invalid', e => {
        const pane = e.target.closest('.tab-pane');
        if (pane && pane.hidden) activar(tabs.find(t => t.getAttribute('aria-controls') === pane.id));
    }, true);
})();

async function cargarCosto(row) {
    const id = $('.i-art', row).value;
    if (!id) return null;
    const r = await fetch(`{{ url('/articulos') }}/${id}/precio`, { headers: { Accept: 'application/json' } });
    const d = await r.json();
    row.dataset.costo = d.costo;
    return d;
}

function aplicarPrecio(row) {
    const costo = num(row.dataset.costo), rec = num($('.i-rec', row).value);
    $('.i-pcio', row).value = (costo + costo * rec / 100).toFixed(2);
    recalcFila(row);
}

function renumerar() { $$('#items-body tr').forEach((r, n) => { $('.i-nro', r).textContent = n + 1; }); }

function enlazarFila(row) {
    $('.i-art', row).addEventListener('change', async () => {
        const d = await cargarCosto(row);
        if (!d) return;
        $('.i-kgs', row).value = d.peso;
        if ($('.i-rec', row).value === '') $('.i-rec', row).value = $('#porc_recargo').value || 0;
        aplicarPrecio(row);
    });
    $('.i-rec', row).addEventListener('change', async () => {
        if (row.dataset.costo === undefined) await cargarCosto(row);
        aplicarPrecio(row);
    });
    $$('.i-cant, .i-kgs, .i-pcio', row).forEach(el => el.addEventListener('input', () => recalcFila(row)));
    $('.i-del', row).addEventListener('click', () => { row.remove(); renumerar(); recalcTotales(); });
}

$('#btn-add').addEventListener('click', () => {
    const html = $('#tpl-item').innerHTML.replaceAll('__I__', nextIndex++);
    $('#items-body').insertAdjacentHTML('beforeend', html);
    const row = $('#items-body').lastElementChild;
    $('.i-rec', row).value = $('#porc_recargo').value || '';
    enlazarFila(row); renumerar(); $('.i-art', row).focus();
});

$$('#items-body tr').forEach(r => { enlazarFila(r); recalcFila(r); });
renumerar();
['#porc_iva', '#porc_iibb'].forEach(s => $(s).addEventListener('input', recalcTotales));
recalcTotales();
</script>
@endpush
