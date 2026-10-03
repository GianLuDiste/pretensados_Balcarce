@extends('layouts.auth')
@section('title', 'Recuperar contraseña')

@section('form')
    <h2>Recuperar contraseña</h2>
    <p class="sub">Ingresá el email de tu usuario y te enviamos un enlace para crear una contraseña nueva.</p>

    @if (session('estado')) <div class="alert ok" role="status">{{ session('estado') }}</div> @endif
    @if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
        </div>
        <button class="btn" type="submit">Enviar enlace</button>
    </form>

    <a class="back" href="{{ route('login') }}">Volver a ingresar</a>
@endsection
