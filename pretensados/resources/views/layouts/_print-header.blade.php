{{-- Encabezado común de todas las impresiones: logo oficial (public/img/logo.png) + datos de config/empresa.php.
     Uso: @include('layouts._print-header')  ·  Opcional: ['titulo' => '...', 'subtitulo' => '...'] a la derecha. --}}
@php($emp = config('empresa'))
<header style="display:flex; justify-content:space-between; align-items:flex-end; gap:1.5rem; border-bottom:2px solid #28324D; padding-bottom:.55rem; margin-bottom:1rem;">
    <div style="display:flex; align-items:flex-end; gap:1rem;">
        <img src="{{ asset('img/logo.png') }}" alt="Pretensados Balcarce" style="height:68px; width:auto;">
        <div style="font-size:.86em; line-height:1.35;">
            <strong style="font-size:1.12em; color:#28324D;">{{ $emp['razon_social'] }}</strong><br>
            {{ $emp['direccion'] }}<br>
            Tel. {{ $emp['telefono'] }} · {{ $emp['email'] }}<br>
            {{ $emp['iva'] }}
        </div>
    </div>
    @isset($titulo)
        <div style="text-align:right; white-space:nowrap;">
            <div style="font-size:1.3em; font-weight:700; color:#28324D; letter-spacing:.02em;">{{ $titulo }}</div>
            @isset($subtitulo)<div>{{ $subtitulo }}</div>@endisset
        </div>
    @endisset
</header>
