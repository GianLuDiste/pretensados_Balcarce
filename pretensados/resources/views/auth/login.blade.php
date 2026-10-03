@extends('layouts.auth')
@section('title', 'Ingresar')

@section('form')
    <h2>Ingresar</h2>
    <p class="sub">Usá tu usuario y contraseña del sistema.</p>

    @if (session('estado')) <div class="alert ok" role="status">{{ session('estado') }}</div> @endif
    @if ($errors->any())
        <div class="alert err" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}" novalidate>
        @csrf
        <div class="field">
            <label for="username">Nombre de usuario</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}"
                   autocomplete="username" autocapitalize="none" spellcheck="false" autofocus required>
        </div>

        <div class="field">
            <label for="password">Contraseña</label>
            <div class="pw">
                <input id="password" type="password" name="password" autocomplete="current-password" required>
                <button type="button" id="ver-clave" aria-controls="password" aria-pressed="false">Mostrar</button>
            </div>
        </div>

        <div class="row">
            <label class="check"><input type="checkbox" name="recordarme" value="1" @checked(old('recordarme'))> Recordarme en este equipo</label>
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </div>

        <button class="btn" type="submit">Ingresar</button>
    </form>
@endsection

@push('scripts')
<script>
    const btn = document.getElementById('ver-clave'), campo = document.getElementById('password');
    btn.addEventListener('click', () => {
        const ver = campo.type === 'password';
        campo.type = ver ? 'text' : 'password';
        btn.textContent = ver ? 'Ocultar' : 'Mostrar';
        btn.setAttribute('aria-pressed', ver);
    });
</script>
@endpush
