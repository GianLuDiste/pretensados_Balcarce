@extends('layouts.app')
@section('title', $acceso->exists ? 'Acceso' : 'Nuevo acceso')

@php
    $v = fn ($k) => old($k, $acceso->{$k});
    $soloLectura = ! auth()->user()->puedeModificar('accesos');
@endphp

@section('content')
<h1>{{ $acceso->exists ? "Acceso «{$acceso->nombre}»" : 'Nuevo acceso' }}</h1>

@if ($errors->any())
    <div class="alert err" role="alert"><strong>Revisá estos datos:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $acceso->exists ? route('accesos.update', $acceso) : route('accesos.store') }}" autocomplete="off">
    @csrf
    @if ($acceso->exists) @method('PUT') @endif

    <fieldset class="plano" @disabled($soloLectura)>
    <section class="panel">
        <div class="grid">
            <div><label for="codigo">Código</label>
                @if ($acceso->exists)
                    <input id="codigo" value="{{ $acceso->codigo }}" readonly>
                @else
                    <input id="codigo" name="codigo" maxlength="50" value="{{ old('codigo') }}" placeholder="ej.: ordenes_compra" autocapitalize="none" required>
                @endif
            </div>
            <div><label for="nombre">Nombre (como se ve en el menú)</label><input id="nombre" name="nombre" maxlength="100" value="{{ $v('nombre') }}" required></div>
            <div><label for="orden">Orden</label><input id="orden" type="number" min="0" name="orden" value="{{ $v('orden') }}"></div>
            <div class="span2"><label for="descripcion">Descripción</label><input id="descripcion" name="descripcion" maxlength="255" value="{{ $v('descripcion') }}"></div>
        </div>
        <p class="info">
            @if ($acceso->exists)
                El código no se puede cambiar: lo usa el sistema para proteger la pantalla.
                @if ($acceso->esDelSistema()) Es un acceso del sistema y no se puede eliminar. @endif
                Está asignado a {{ $acceso->perfiles_count }} perfil(es).
            @else
                El código identifica la opción en el sistema y no se puede cambiar después. Un acceso nuevo sirve
                cuando se agregue la pantalla correspondiente al menú.
            @endif
        </p>
    </section>
    </fieldset>

    <div class="actions">
        @unless ($soloLectura) <button class="btn">Guardar</button> @endunless
        <a class="btn sec" href="{{ route('accesos.index') }}">{{ $soloLectura ? 'Volver' : 'Cancelar' }}</a>
        @if ($acceso->exists && ! $acceso->esDelSistema() && ! $soloLectura)
            <button class="btn danger" form="form-eliminar"
                onclick="return confirm('¿Confirma la eliminación del acceso «{{ addslashes($acceso->nombre) }}»?')">Eliminar</button>
        @endif
    </div>
</form>

@if ($acceso->exists && ! $acceso->esDelSistema())
    <form id="form-eliminar" method="POST" action="{{ route('accesos.destroy', $acceso) }}">@csrf @method('DELETE')</form>
@endif
@endsection
