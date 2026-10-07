@extends('layouts.app')
@section('title', 'Accesos')
@section('content')
@php($puedeModificar = auth()->user()->puedeModificar('accesos'))
<h1>Accesos</h1>

@if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

<p class="info">Cada acceso es una opción del menú. En cada perfil se le asigna <strong>solo consulta</strong> o <strong>modificación</strong>.</p>
@if ($puedeModificar)
    <div class="actions" style="margin-bottom:1rem"><a class="btn" href="{{ route('accesos.create') }}">Nuevo acceso</a></div>
@endif

<section class="panel scroll">
    <table>
        <thead><tr><th class="num">Orden</th><th>Acceso</th><th>Código</th><th>Descripción</th><th class="num">Perfiles</th><th></th></tr></thead>
        <tbody>
        @forelse ($accesos as $a)
            <tr>
                <td class="num">{{ $a->orden }}</td>
                <td><strong>{{ $a->nombre }}</strong></td>
                <td><code>{{ $a->codigo }}</code> @if ($a->esDelSistema()) <span class="badge" title="Lo usa el sistema: no se puede eliminar">sistema</span> @endif</td>
                <td>{{ $a->descripcion }}</td>
                <td class="num">{{ $a->perfiles_count }}</td>
                <td class="actions">
                    <a href="{{ route('accesos.edit', $a) }}">{{ $puedeModificar ? 'Editar' : 'Ver' }}</a>
                    @if ($puedeModificar && ! $a->esDelSistema())
                        <form method="POST" action="{{ route('accesos.destroy', $a) }}"
                              onsubmit="return confirm('¿Confirma la eliminación del acceso «{{ addslashes($a->nombre) }}»?')">
                            @csrf @method('DELETE')
                            <button class="btn danger" style="padding:.15rem .6rem">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="info">No hay accesos.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
