@php
    $u = auth()->user();
    $enCotiz = request()->routeIs('cotizaciones.*');
    $enClientes = request()->routeIs('clientes.*');
@endphp

<aside id="sidebar" class="sidebar" aria-label="Menú principal">
    <nav>
        <ul class="menu">

            {{-- Nivel 1: Inicio (logo) --}}
            <li>
                <a class="item home-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"
                   title="Inicio" aria-label="Inicio" @if (request()->routeIs('home')) aria-current="page" @endif>
                    @include('layouts._logo-mark')
                    <span class="txt wordmark">pretensados<br>balcarce</span>
                </a>
            </li>

            {{-- Nivel 1: Cotizaciones → Nivel 2: Consulta / Nueva cotización --}}
            @if ($u->puede('C'))
                <li class="has-sub {{ $enCotiz ? 'open active' : '' }}">
                    <button type="button" class="item" aria-expanded="{{ $enCotiz ? 'true' : 'false' }}" aria-controls="sub-cotizaciones" title="Cotizaciones">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h6"/>
                        </svg>
                        <span class="txt">Cotizaciones</span>
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <ul class="sub" id="sub-cotizaciones">
                        <li class="sub-title" aria-hidden="true">Cotizaciones</li>
                        <li><a class="subitem {{ request()->routeIs('cotizaciones.index', 'cotizaciones.edit', 'cotizaciones.newVersion') ? 'active' : '' }}"
                               href="{{ route('cotizaciones.index') }}">Consulta</a></li>
                        @if ($u->puede('A'))
                            <li><a class="subitem {{ request()->routeIs('cotizaciones.create') ? 'active' : '' }}"
                                   href="{{ route('cotizaciones.create') }}">Nueva cotización</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Nivel 1: Clientes → Nivel 2: Consulta / Nuevo cliente --}}
            @if ($u->puede('C'))
                <li class="has-sub {{ $enClientes ? 'open active' : '' }}">
                    <button type="button" class="item" aria-expanded="{{ $enClientes ? 'true' : 'false' }}" aria-controls="sub-clientes" title="Clientes">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10"/><path d="M2 21h20"/><path d="M8 7h4M8 11h4M8 15h4"/>
                        </svg>
                        <span class="txt">Clientes</span>
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <ul class="sub" id="sub-clientes">
                        <li class="sub-title" aria-hidden="true">Clientes</li>
                        <li><a class="subitem {{ request()->routeIs('clientes.index', 'clientes.edit') ? 'active' : '' }}"
                               href="{{ route('clientes.index') }}">Consulta</a></li>
                        @if ($u->puede('A'))
                            <li><a class="subitem {{ request()->routeIs('clientes.create') ? 'active' : '' }}"
                                   href="{{ route('clientes.create') }}">Nuevo cliente</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Nivel 1: Usuarios (solo administradores) --}}
            @if ($u->es_admin)
                <li>
                    <a class="item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}" title="Usuarios">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><circle cx="17" cy="9" r="2.5"/><path d="M17 14.2c2.4.3 4 2.1 4 4.8"/>
                        </svg>
                        <span class="txt">Usuarios</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
