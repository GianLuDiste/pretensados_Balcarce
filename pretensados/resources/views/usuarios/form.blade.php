@extends('layouts.app')
@section('title', $usuario->exists ? 'Editar usuario' : 'Nuevo usuario')

@php
    $v = fn ($k) => old($k, $usuario->{$k});
    $marcados = old('permisos', str_split((string) $usuario->permisos));
    $esAdmin = (bool) old('es_admin', $usuario->es_admin);
    $activo = (bool) old('activo', $usuario->activo);
@endphp

@section('content')
<h1>{{ $usuario->exists ? 'Editar usuario' : 'Nuevo usuario' }}</h1>

@if ($errors->any())
    <div class="alert err" role="alert"><strong>Revisá estos datos:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $usuario->exists ? route('usuarios.update', $usuario) : route('usuarios.store') }}" autocomplete="off">
    @csrf
    @if ($usuario->exists) @method('PUT') @endif

    <section class="panel">
        <h2>Datos</h2>
        <div class="grid">
            <div class="span2"><label for="name">Nombre completo</label><input id="name" name="name" maxlength="100" value="{{ $v('name') }}" required></div>
            <div><label for="username">Nombre de usuario</label><input id="username" name="username" maxlength="50" value="{{ $v('username') }}" autocapitalize="none" required></div>
            <div><label for="email">Email</label><input id="email" type="email" name="email" maxlength="100" value="{{ $v('email') }}" required></div>
            <div><label for="telefono">Teléfono</label><input id="telefono" name="telefono" maxlength="30" value="{{ $v('telefono') }}"></div>
        </div>
    </section>

    <section class="panel">
        <h2>Contraseña</h2>
        <div class="grid">
            <div><label for="password">{{ $usuario->exists ? 'Contraseña nueva' : 'Contraseña' }}</label>
                <input id="password" type="password" name="password" autocomplete="new-password" {{ $usuario->exists ? '' : 'required' }}></div>
            <div><label for="password_confirmation">Repetir contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"></div>
        </div>
        <p class="info">{{ $usuario->exists ? 'Dejala vacía si no querés cambiarla. ' : '' }}Mínimo 8 caracteres.</p>
    </section>

    <section class="panel">
        <h2>Permisos</h2>
        <input type="hidden" name="es_admin" value="0">
        <label class="check-line"><input type="checkbox" id="es_admin" name="es_admin" value="1" @checked($esAdmin)> Administrador (todos los permisos y gestión de usuarios)</label>

        <fieldset id="permisos" style="margin-top:.75rem" @disabled($esAdmin)>
            <legend>Acciones permitidas</legend>
            <input type="hidden" name="permisos[]" value="">
            @foreach (\App\Models\User::PERMISOS as $letra => $nombre)
                <label class="check-line"><input type="checkbox" name="permisos[]" value="{{ $letra }}" @checked(in_array($letra, $marcados))> {{ $nombre }}</label>
            @endforeach
            <p class="info" style="margin:.5rem 0 0">Cualquier permiso incluye Consulta: sin ella no se puede abrir la pantalla.</p>
        </fieldset>

        <input type="hidden" name="activo" value="0">
        <label class="check-line" style="margin-top:.9rem"><input type="checkbox" name="activo" value="1" @checked($activo)> Usuario activo (si se desmarca, no puede ingresar)</label>
    </section>

    <div class="actions">
        <button class="btn">Guardar</button>
        <a class="btn sec" href="{{ route('usuarios.index') }}">Cancelar</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const adm = document.getElementById('es_admin'), perm = document.getElementById('permisos');
    adm.addEventListener('change', () => { perm.disabled = adm.checked; });
</script>
@endpush
