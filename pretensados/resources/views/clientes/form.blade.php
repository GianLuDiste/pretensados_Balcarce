@extends('layouts.app')
@section('title', $cliente->exists ? 'Cliente' : 'Nuevo cliente')

@php
    $u = auth()->user();
    $editar = $cliente->exists;
    $v = fn ($campo, $col) => old($campo, $cliente->getAttribute($col));
    $puedeGuardar = $u->puedeModificar('clientes');
@endphp

@section('content')
<h1>{{ $editar ? "Cliente {$cliente->CODIGO}" : 'Nuevo cliente' }}</h1>

@if ($errors->any())
    <div class="alert err" role="alert"><strong>Revisá estos datos:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $editar ? route('clientes.update', $cliente) : route('clientes.store') }}" autocomplete="off">
    @csrf
    @if ($editar) @method('PUT') @endif

    <fieldset style="border:0; padding:0; margin:0; min-width:0" @disabled(! $puedeGuardar)>
    <section class="panel">
        <h2>Datos generales</h2>
        <div class="grid">
            <div class="span2"><label for="razon_social">Razón social</label>
                <input id="razon_social" name="razon_social" maxlength="100" value="{{ $v('razon_social', 'RAZON-SOCIAL') }}"
                       style="text-transform:uppercase" required autofocus></div>
            <div class="span2"><label for="direccion">Dirección</label>
                <input id="direccion" name="direccion" maxlength="50" value="{{ $v('direccion', 'DIRECCION') }}"></div>
            <div><label for="telefono">Teléfono</label>
                <input id="telefono" name="telefono" maxlength="20" value="{{ $v('telefono', 'TELEFONO') }}"></div>
            <div><label for="fax">Fax</label>
                <input id="fax" name="fax" maxlength="20" value="{{ $v('fax', 'FAX') }}"></div>
        </div>
    </section>

    <section class="panel">
        <h2>Ubicación</h2>
        <div class="grid">
            <div><label for="localidad">Localidad</label>
                <select id="localidad" name="localidad" required>
                    <option value="">Elegí una localidad</option>
                    @foreach ($ciudades as $ci)
                        <option value="{{ $ci->CODIGO }}" data-cp="{{ $ci->getAttribute('COD-POST') ?: '' }}"
                            @selected($v('localidad', 'LOCALIDAD') == $ci->CODIGO)>{{ $ci->DESCRIPCION }}</option>
                    @endforeach
                </select></div>
            <div><label for="cod_postal">Código postal</label>
                <input id="cod_postal" type="number" min="0" name="cod_postal" value="{{ $v('cod_postal', 'COD-POSTAL') ?: '' }}"></div>
            <div><label for="provincia">Provincia</label>
                <select id="provincia" name="provincia" required>
                    <option value="">Elegí una provincia</option>
                    @foreach ($provincias as $p)<option value="{{ $p->CODIGO }}" @selected($v('provincia', 'PROVINCIA') == $p->CODIGO)>{{ $p->DESCRIPCION }}</option>@endforeach
                </select></div>
        </div>
    </section>

    <section class="panel">
        <h2>Datos fiscales y contacto</h2>
        <div class="grid">
            <div><label for="iva">Condición de IVA</label>
                <select id="iva" name="iva" required>
                    <option value="">Elegí una condición</option>
                    @foreach ($condiciones as $ci)<option value="{{ $ci->CODIGO }}" @selected($v('iva', 'IVA') == $ci->CODIGO)>{{ $ci->DESCRIPCION }}</option>@endforeach
                </select></div>
            <div><label for="cuit">CUIT</label>
                <input id="cuit" name="cuit" maxlength="20" value="{{ $v('cuit', 'CUIT') }}" placeholder="30-12345678-9"></div>
            <div class="span2"><label for="contacto">Contacto</label>
                <input id="contacto" name="contacto" maxlength="50" value="{{ $v('contacto', 'CONTACTO') }}"></div>
            <div class="span2"><label for="email">E-mail</label>
                <input id="email" name="email" maxlength="50" value="{{ $v('email', 'E-MAIL') }}"></div>
        </div>
    </section>
    </fieldset>

    <div class="actions">
        @if ($puedeGuardar) <button class="btn">Guardar</button> @endif
        <a class="btn sec" href="{{ route('clientes.index') }}">{{ $puedeGuardar ? 'Cancelar' : 'Volver' }}</a>
        @if ($editar && $puedeGuardar)
            <button class="btn danger" form="form-eliminar"
                onclick="return confirm('¿Confirma el borrado del cliente {{ $cliente->CODIGO }} - {{ addslashes($cliente->razon_social) }}?')">Eliminar</button>
        @endif
    </div>
</form>

@if ($editar)
    <form id="form-eliminar" method="POST" action="{{ route('clientes.destroy', $cliente) }}">@csrf @method('DELETE')</form>
@endif
@endsection

@push('scripts')
<script>
// Combo2_KeyPress del VB: al elegir la localidad propone su código postal (en el alta, o si el CP está vacío).
const esAlta = {{ $editar ? 'false' : 'true' }};
const loc = document.getElementById('localidad'), cp = document.getElementById('cod_postal');
loc.addEventListener('change', () => {
    const sugerido = loc.selectedOptions[0]?.dataset.cp;
    if (sugerido && (esAlta || cp.value === '')) cp.value = sugerido;
});
</script>
@endpush
