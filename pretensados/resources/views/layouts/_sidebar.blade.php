@php
    $u = auth()->user();
    $enCotiz = request()->routeIs('cotizaciones.*');
    $enClientes = request()->routeIs('clientes.*');
    $enSeguridad = request()->routeIs('usuarios.*', 'perfiles.*', 'accesos.*');
    $verSeguridad = $u->puedeVer('usuarios') || $u->puedeVer('perfiles') || $u->puedeVer('accesos');
@endphp

<aside id="sidebar" class="sidebar" aria-label="Menú principal">
    <nav>
        <ul class="menu">

            {{-- Nivel 1: Inicio (logo) --}}
            <li>
                <a class="item home-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"
                   title="Inicio" aria-label="Inicio" @if (request()->routeIs('home')) aria-current="page" @endif>
                    @include('layouts._logo-mark')
                </a>
            </li>

            {{-- Nivel 1: Cotizaciones → Nivel 2: Consulta / Nueva cotización --}}
            @if ($u->puedeVer('cotizaciones'))
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
                        @if ($u->puedeModificar('cotizaciones'))
                            <li><a class="subitem {{ request()->routeIs('cotizaciones.create') ? 'active' : '' }}"
                                   href="{{ route('cotizaciones.create') }}">Nueva cotización</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Nivel 1: Clientes → Nivel 2: Consulta / Nuevo cliente --}}
            @if ($u->puedeVer('clientes'))
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
                        @if ($u->puedeModificar('clientes'))
                            <li><a class="subitem {{ request()->routeIs('clientes.create') ? 'active' : '' }}"
                                   href="{{ route('clientes.create') }}">Nuevo cliente</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Nivel 1: Seguridad → Nivel 2: Usuarios / Perfiles / Accesos (cada uno es un acceso) --}}
            @if ($verSeguridad)
                <li class="has-sub {{ $enSeguridad ? 'open active' : '' }}">
                    <button type="button" class="item" aria-expanded="{{ $enSeguridad ? 'true' : 'false' }}" aria-controls="sub-seguridad" title="Seguridad">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3 4.5 6v5.5c0 4.6 3.2 8.4 7.5 9.5 4.3-1.1 7.5-4.9 7.5-9.5V6z"/><path d="m9 12 2.2 2.2L15.5 10"/>
                        </svg>
                        <span class="txt">Seguridad</span>
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <ul class="sub" id="sub-seguridad">
                        <li class="sub-title" aria-hidden="true">Seguridad</li>
                        @if ($u->puedeVer('usuarios'))
                            <li><a class="subitem {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">Usuarios</a></li>
                        @endif
                        @if ($u->puedeVer('perfiles'))
                            <li><a class="subitem {{ request()->routeIs('perfiles.*') ? 'active' : '' }}" href="{{ route('perfiles.index') }}">Perfiles</a></li>
                        @endif
                        @if ($u->puedeVer('accesos'))
                            <li><a class="subitem {{ request()->routeIs('accesos.*') ? 'active' : '' }}" href="{{ route('accesos.index') }}">Accesos</a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</aside>
