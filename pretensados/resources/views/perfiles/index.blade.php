@extends('layouts.app')
@section('title', 'Perfiles')
@section('content')
@php($puedeModificar = auth()->user()->puedeModificar('perfiles'))
<h1>Perfiles</h1>

@if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

@if ($puedeModificar)
    <div class="actions" style="margin-bottom:1rem"><a class="btn" href="{{ route('perfiles.create') }}">Nuevo perfil</a></div>
@endif

<section class="panel scroll">
    <table>
        <thead><tr><th>Perfil</th><th>Descripción</th><th class="num">Accesos</th><th class="num">Usuarios</th><th></th></tr></thead>
        <tbody>
        @forelse ($perfiles as $p)
            <tr>
                <td><strong>{{ $p->nombre }}</strong> @if ($p->es_admin) <span class="badge adm">acceso total</span> @endif</td>
                <td>{{ $p->descripcion }}</td>
                <td class="num">{{ $p->es_admin ? 'Todos' : $p->accesos_count }}</td>
                <td class="num">{{ $p->usuarios_count }}</td>
                <td class="actions">
                    <a href="{{ route('perfiles.edit', $p) }}">{{ $puedeModificar ? 'Editar' : 'Ver' }}</a>
                    @if ($puedeModificar && ! $p->es_admin)
                        <form method="POST" action="{{ route('perfiles.destroy', $p) }}"
                              onsubmit="return confirm('¿Confirma la eliminación del perfil «{{ addslashes($p->nombre) }}»?{{ $p->usuarios_count ? ' Se quitará a ' . $p->usuarios_count . ' usuario(s).' : '' }}')">
                            @csrf @method('DELETE')
                            <button class="btn danger" style="padding:.15rem .6rem">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="info">No hay perfiles.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
