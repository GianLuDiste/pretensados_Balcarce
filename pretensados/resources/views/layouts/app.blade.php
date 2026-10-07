<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cotizaciones') · Pretensados Balcarce</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}?v={{ filemtime(public_path('css/brand.css')) }}">
    {{-- Recuerda si el menú quedó reducido; va en el <head> para evitar el "salto" al cargar --}}
    <script>try { if (localStorage.getItem('nav') === 'collapsed') document.documentElement.classList.add('nav-collapsed'); } catch (e) {}</script>
</head>
<body>
<div class="app">
    @auth @include('layouts._sidebar') @endauth
    <div class="scrim" id="scrim"></div>

    <div class="content">
        <header class="topbar">
            @auth
                <button type="button" class="burger" id="nav-toggle" aria-controls="sidebar" aria-expanded="true" aria-label="Mostrar u ocultar el menú">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="quien">
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit">Cerrar sesión</button></form>
                </div>
            @endauth
        </header>

        <main class="@yield('main_class', 'page')">
            @if (session('ok')) <div class="alert ok" role="status">{{ session('ok') }}</div> @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
(function () {
    var root = document.documentElement, btn = document.getElementById('nav-toggle');
    if (!btn) return;
    var movil = window.matchMedia('(max-width: 900px)');

    function sync() {
        btn.setAttribute('aria-expanded', movil.matches ? root.classList.contains('nav-open') : !root.classList.contains('nav-collapsed'));
    }
    btn.addEventListener('click', function () {
        if (movil.matches) {
            root.classList.toggle('nav-open');
        } else {
            root.classList.toggle('nav-collapsed');
            try { localStorage.setItem('nav', root.classList.contains('nav-collapsed') ? 'collapsed' : 'expanded'); } catch (e) {}
        }
        sync();
    });
    document.getElementById('scrim').addEventListener('click', function () { root.classList.remove('nav-open'); sync(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && root.classList.contains('nav-open')) { root.classList.remove('nav-open'); sync(); btn.focus(); }
    });
    movil.addEventListener('change', function () { root.classList.remove('nav-open'); sync(); });

    // Grupos del primer nivel (Cotizaciones): abrir / cerrar el segundo nivel
    document.querySelectorAll('.has-sub > button').forEach(function (b) {
        b.addEventListener('click', function () {
            var li = b.parentElement, abierto = !li.classList.contains('open');
            li.classList.toggle('open', abierto);
            b.setAttribute('aria-expanded', abierto);
        });
    });
    sync();
})();
</script>
@stack('scripts')
</body>
</html>
