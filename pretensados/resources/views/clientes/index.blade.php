@extends('layouts.app')
@section('title', 'Clientes')
@section('content')
@php($u = auth()->user())
<h1>Clientes</h1>

@if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

<section class="panel">
    <form method="GET" class="grid" style="align-items:end">
        <div><label for="q">Código, razón social, CUIT o contacto</label><input id="q" name="q" value="{{ request('q') }}"></div>
        <div><label for="localidad">Localidad</label>
            <select id="localidad" name="localidad">
                <option value="">Todas</option>
                @foreach ($ciudades as $ci)<option value="{{ $ci->CODIGO }}" @selected(request('localidad') == $ci->CODIGO)>{{ $ci->DESCRIPCION }}</option>@endforeach
            </select></div>
        <div class="actions">
            <button class="btn">Buscar</button>
            <a class="btn sec" href="{{ route('clientes.index') }}">Limpiar</a>
            @if ($u->puedeModificar('clientes')) <a class="btn" href="{{ route('clientes.create') }}">Nuevo cliente</a> @endif
            <a class="btn sec" target="_blank" href="{{ route('clientes.print', request()->only('q', 'localidad')) }}">Imprimir listado</a>
        </div>
    </form>
</section>

<section class="panel scroll">
    <table>
        <thead><tr>
            <th class="num">Código</th><th>Razón social</th><th>Localidad</th><th>Teléfono</th><th>CUIT</th><th>IVA</th><th>Contacto</th><th></th>
        </tr></thead>
        <tbody>
        @forelse ($clientes as $c)
            <tr>
                <td class="num">{{ $c->CODIGO }}</td>
                <td><strong>{{ $c->razon_social }}</strong></td>
                <td>{{ $c->ciudad?->DESCRIPCION }}</td>
                <td>{{ $c->TELEFONO }}</td>
                <td>{{ $c->CUIT }}</td>
                <td>{{ $c->condicionIva?->DESCRIPCION }}</td>
                <td>{{ $c->CONTACTO }}</td>
                <td class="actions"><a href="{{ route('clientes.edit', $c) }}">Abrir</a></td>
            </tr>
        @empty
            <tr><td colspan="8" class="info">No hay clientes para esa búsqueda.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
{{ $clientes->links('vendor.pagination.brand') }}
@endsection
