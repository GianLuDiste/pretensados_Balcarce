@extends('layouts.app')
@section('title', $perfil->exists ? 'Perfil' : 'Nuevo perfil')

@php
    $v = fn ($k) => old($k, $perfil->{$k});
    $nivelDe = fn ($a) => old("accesos.{$a->id}", $niveles[$a->codigo] ?? '');
    $usuariosMarcados = array_map('intval', old('usuarios', $perfil->usuarios->pluck('id')->all()));
@endphp

@section('content')
<h1>{{ $perfil->exists ? "Perfil «{$perfil->nombre}»" : 'Nuevo perfil' }}</h1>

@if ($errors->any())
    <div class="alert err" role="alert"><strong>Revisá estos datos:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $perfil->exists ? route('perfiles.update', $perfil) : route('perfiles.store') }}" autocomplete="off">
    @csrf
    @if ($perfil->exists) @method('PUT') @endif

    <fieldset class="plano" @disabled($soloLectura)>
    <section class="panel">
        <h2>Datos</h2>
        <div class="grid">
            <div><label for="nombre">Nombre</label><input id="nombre" name="nombre" maxlength="100" value="{{ $v('nombre') }}" required></div>
            <div class="span2"><label for="descripcion">Descripción</label><input id="descripcion" name="descripcion" maxlength="255" value="{{ $v('descripcion') }}"></div>
        </div>
    </section>

    <section class="panel scroll">
        <h2>Accesos del perfil</h2>
        @if ($perfil->es_admin)
            <p class="info" style="margin:0">El perfil Administrador tiene <strong>modificación en todos los accesos</strong>, incluidos los que se agreguen en el futuro. No necesita configuración.</p>
        @else
            <p class="info" style="margin-top:0"><strong>Solo consulta:</strong> ver, buscar e imprimir. <strong>Modificación:</strong> además, dar de alta, modificar y eliminar.</p>
            <table>
                <thead><tr><th>Opción del menú</th><th>Nivel</th></tr></thead>
                <tbody>
                @foreach ($accesos as $a)
                    <tr>
                        <td><strong>{{ $a->nombre }}</strong>@if ($a->descripcion)<br><span class="info">{{ $a->descripcion }}</span>@endif</td>
                        <td>
                            <div class="niveles" role="radiogroup" aria-label="Nivel en {{ $a->nombre }}">
                                <label class="check-line"><input type="radio" name="accesos[{{ $a->id }}]" value="" @checked($nivelDe($a) === '')> Sin acceso</label>
                                @foreach (\App\Models\Acceso::NIVELES as $cod => $nombre)
                                    <label class="check-line"><input type="radio" name="accesos[{{ $a->id }}]" value="{{ $cod }}" @checked($nivelDe($a) === $cod)> {{ $nombre }}</label>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <section class="panel">
        <h2>Usuarios con este perfil</h2>
        @if ($usuarios->isEmpty())
            <p class="info" style="margin:0">No hay usuarios cargados.</p>
        @else
            <div class="grid">
                @foreach ($usuarios as $u)
                    <label class="check-line">
                        <input type="checkbox" name="usuarios[]" value="{{ $u->id }}" @checked(in_array($u->id, $usuariosMarcados, true))>
                        {{ $u->name }} <span class="info">({{ $u->username }})</span>
                        @unless ($u->activo) <span class="badge off">Inactivo</span> @endunless
                    </label>
                @endforeach
            </div>
        @endif
    </section>
    </fieldset>

    <div class="actions">
        @unless ($soloLectura) <button class="btn">Guardar</button> @endunless
        <a class="btn sec" href="{{ route('perfiles.index') }}">{{ $soloLectura ? 'Volver' : 'Cancelar' }}</a>
        @if ($perfil->exists && ! $perfil->es_admin && ! $soloLectura)
            <button class="btn danger" form="form-eliminar"
                onclick="return confirm('¿Confirma la eliminación del perfil «{{ addslashes($perfil->nombre) }}»?')">Eliminar</button>
        @endif
    </div>
</form>

@if ($perfil->exists && ! $perfil->es_admin)
    <form id="form-eliminar" method="POST" action="{{ route('perfiles.destroy', $perfil) }}">@csrf @method('DELETE')</form>
@endif
@endsection
