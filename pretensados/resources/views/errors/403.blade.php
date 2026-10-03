@extends('layouts.app')
@section('title', 'Sin autorización')
@section('content')
<div class="estado-vacio">
    <div class="codigo">403</div>
    <h1 style="display:inline-block">Sin autorización</h1>
    <p>{{ $exception->getMessage() ?: 'No tenés permiso para ver esta pantalla.' }}</p>
    <a class="btn" href="{{ route('home') }}">Volver al inicio</a>
</div>
@endsection
