@extends('layouts.app')
@section('title', 'No encontrado')
@section('content')
<div class="estado-vacio">
    <div class="codigo">404</div>
    <h1 style="display:inline-block">No encontramos lo que buscabas</h1>
    <p>La página o el registro no existe, o fue eliminado.</p>
    <a class="btn" href="{{ route('home') }}">Volver al inicio</a>
</div>
@endsection
