@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<h1>Usuarios</h1>

@if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

<section class="panel">
    <form method="GET" class="grid" style="align-items:end">
        <div><label for="q">Buscar por nombre, usuario o email</label><input id="q" name="q" value="{{ request('q') }}"></div>
        <div class="actions">
            <button class="btn">Buscar</button>
            <a class="btn sec" href="{{ route('usuarios.index') }}">Limpiar</a>
            <a class="btn" href="{{ route('usuarios.create') }}">Nuevo usuario</a>
        </div>
    </form>
</section>

<section class="panel scroll">
    <table>
        <thead><tr><th>Usuario</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Permisos</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @forelse ($usuarios as $u)
            <tr>
                <td><strong>{{ $u->username }}</strong></td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->telefono }}</td>
                <td>
                    @if ($u->es_admin) <span class="badge adm">Administrador</span>
                    @else @foreach (str_split((string) $u->permisos) as $l)<span class="badge" title="{{ \App\Models\User::PERMISOS[$l] ?? $l }}">{{ \App\Models\User::PERMISOS[$l] ?? $l }}</span>@endforeach
                    @endif
                </td>
                <td>@if ($u->activo) Activo @else <span class="badge off">Inactivo</span> @endif</td>
                <td class="actions">
                    <a href="{{ route('usuarios.edit', $u) }}">Editar</a>
                    @unless ($u->is(auth()->user()))
                        <form method="POST" action="{{ route('usuarios.destroy', $u) }}"
                              onsubmit="return confirm('¿Confirma la eliminación del usuario {{ $u->username }}?')">
                            @csrf @method('DELETE')
                            <button class="btn danger" style="padding:.15rem .6rem">Eliminar</button>
                        </form>
                    @endunless
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
