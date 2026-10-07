@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
@php($puedeModificar = auth()->user()->puedeModificar('usuarios'))
<h1>Usuarios</h1>

@if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

<section class="panel">
    <form method="GET" class="grid" style="align-items:end">
        <div><label for="q">Buscar por nombre, usuario o email</label><input id="q" name="q" value="{{ request('q') }}"></div>
        <div><label for="perfil">Perfil</label>
            <select id="perfil" name="perfil">
                <option value="">Todos</option>
                @foreach ($perfiles as $p)<option value="{{ $p->id }}" @selected(request('perfil') == $p->id)>{{ $p->nombre }}</option>@endforeach
            </select></div>
        <div class="actions">
            <button class="btn">Buscar</button>
            <a class="btn sec" href="{{ route('usuarios.index') }}">Limpiar</a>
            @if ($puedeModificar) <a class="btn" href="{{ route('usuarios.create') }}">Nuevo usuario</a> @endif
        </div>
    </form>
</section>

<section class="panel scroll">
    <table>
        <thead><tr><th>Usuario</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Perfiles</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @forelse ($usuarios as $u)
            <tr>
                <td><strong>{{ $u->username }}</strong></td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->telefono }}</td>
                <td>
                    @forelse ($u->perfiles as $p)<span class="badge {{ $p->es_admin ? 'adm' : '' }}">{{ $p->nombre }}</span>@empty<span class="info">Sin perfiles</span>@endforelse
                </td>
                <td>@if ($u->activo) Activo @else <span class="badge off">Inactivo</span> @endif</td>
                <td class="actions">
                    <a href="{{ route('usuarios.edit', $u) }}">{{ $puedeModificar ? 'Editar' : 'Ver' }}</a>
                    @if ($puedeModificar && ! $u->is(auth()->user()))
                        <form method="POST" action="{{ route('usuarios.destroy', $u) }}"
                              onsubmit="return confirm('¿Confirma la eliminación del usuario {{ $u->username }}?')">
                            @csrf @method('DELETE')
                            <button class="btn danger" style="padding:.15rem .6rem">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="info">No hay usuarios para esa búsqueda.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
{{ $usuarios->links('vendor.pagination.brand') }}
@endsection
