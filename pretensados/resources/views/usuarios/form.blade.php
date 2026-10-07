@extends('layouts.app')
@section('title', $usuario->exists ? 'Usuario' : 'Nuevo usuario')

@php
    $v = fn ($k) => old($k, $usuario->{$k});
    $marcados = array_map('intval', old('perfiles', $usuario->exists ? $usuario->perfiles->pluck('id')->all() : []));
    $activo = (bool) old('activo', $usuario->activo);
    // Solo lectura: sin permiso de modificación, o un no-administrador mirando a un administrador.
    $soloLectura = ! auth()->user()->puedeModificar('usuarios') || ($usuario->exists && $usuario->esAdmin() && ! $actorEsAdmin);
@endphp

@section('content')
<h1>{{ $usuario->exists ? "Usuario {$usuario->username}" : 'Nuevo usuario' }}</h1>

@if ($errors->any())
    <div class="alert err" role="alert"><strong>Revisá estos datos:</strong>
        <ul style="margin:.4rem 0 0 1.1rem; padding:0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $usuario->exists ? route('usuarios.update', $usuario) : route('usuarios.store') }}" autocomplete="off">
    @csrf
    @if ($usuario->exists) @method('PUT') @endif

    <fieldset class="plano" @disabled($soloLectura)>
    <section class="panel">
        <h2>Datos</h2>
        <div class="grid">
            <div class="span2"><label for="name">Nombre completo</label><input id="name" name="name" maxlength="100" value="{{ $v('name') }}" required></div>
            <div><label for="username">Nombre de usuario</label><input id="username" name="username" maxlength="50" value="{{ $v('username') }}" autocapitalize="none" required></div>
            <div><label for="email">Email</label><input id="email" type="email" name="email" maxlength="100" value="{{ $v('email') }}" required></div>
            <div><label for="telefono">Teléfono</label><input id="telefono" name="telefono" maxlength="30" value="{{ $v('telefono') }}"></div>
        </div>
        <input type="hidden" name="activo" value="0">
        <label class="check-line" style="margin-top:.9rem"><input type="checkbox" name="activo" value="1" @checked($activo)> Usuario activo (si se desmarca, no puede ingresar)</label>
    </section>

    @unless ($soloLectura)
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
    @endunless

    <section class="panel">
        <h2>Perfiles</h2>
        <p class="info" style="margin-top:0">El usuario tiene la suma de los accesos de sus perfiles: si un perfil da consulta y otro modificación, queda con modificación.</p>
        <div class="grid" id="perfiles">
            @foreach ($perfiles as $p)
                @php($bloqueado = $p->es_admin && ! $actorEsAdmin)
                <label class="check-line" title="{{ $p->descripcion }}">
                    <input type="checkbox" name="perfiles[]" value="{{ $p->id }}" @checked(in_array($p->id, $marcados, true)) @disabled($bloqueado)
                           data-admin="{{ $p->es_admin ? 1 : 0 }}" data-niveles='@json($p->nivelesPorCodigo())'>
                    {{ $p->nombre }} @if ($p->es_admin) <span class="badge adm">acceso total</span> @endif
                </label>
            @endforeach
        </div>
        @unless ($actorEsAdmin) <p class="info">Solo un administrador puede asignar el perfil Administrador.</p> @endunless
    </section>
    </fieldset>

    <section class="panel scroll">
        <h2>Accesos resultantes</h2>
        <table>
            <thead><tr><th>Opción del menú</th><th>Nivel</th></tr></thead>
            <tbody>
            @foreach ($accesos as $a)
                <tr><td>{{ $a->nombre }}</td><td class="nivel" data-codigo="{{ $a->codigo }}"></td></tr>
            @endforeach
            </tbody>
        </table>
    </section>

    <div class="actions">
        @unless ($soloLectura) <button class="btn">Guardar</button> @endunless
        <a class="btn sec" href="{{ route('usuarios.index') }}">{{ $soloLectura ? 'Volver' : 'Cancelar' }}</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Calcula en vivo la suma de accesos de los perfiles marcados (M gana a C; Administrador = todo).
(function () {
    const checks = [...document.querySelectorAll('#perfiles input[type=checkbox]')];
    const etiqueta = { M: '<span class="badge adm">Modificación</span>', C: '<span class="badge">Solo consulta</span>', '': '<span class="info">Sin acceso</span>' };
    function calcular() {
        const marcados = checks.filter(c => c.checked);
        const admin = marcados.some(c => c.dataset.admin === '1');
        const suma = {};
        marcados.forEach(c => Object.entries(JSON.parse(c.dataset.niveles || '{}')).forEach(([cod, niv]) => {
            if (suma[cod] !== 'M') suma[cod] = niv;
        }));
        document.querySelectorAll('td.nivel').forEach(td => { td.innerHTML = etiqueta[admin ? 'M' : (suma[td.dataset.codigo] || '')]; });
    }
    checks.forEach(c => c.addEventListener('change', calcular));
    calcular();
})();
</script>
@endpush
