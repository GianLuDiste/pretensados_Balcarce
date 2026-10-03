@extends('layouts.auth')
@section('title', 'Nueva contraseña')

@section('form')
    <h2>Nueva contraseña</h2>
    <p class="sub">Elegí una contraseña de al menos 8 caracteres.</p>

    @if ($errors->any()) <div class="alert err" role="alert">{{ $errors->first() }}</div> @endif

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" autocomplete="email" required>
        </div>
        <div class="field">
            <label for="password">Contraseña nueva</label>
            <input id="password" type="password" name="password" autocomplete="new-password" autofocus required>
        </div>
        <div class="field">
            <label for="password_confirmation">Repetí la contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
        </div>
        <button class="btn" type="submit">Guardar contraseña</button>
    </form>

    <a class="back" href="{{ route('login') }}">Volver a ingresar</a>
@endsection
