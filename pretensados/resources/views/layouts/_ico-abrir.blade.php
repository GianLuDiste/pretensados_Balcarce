{{-- Ícono para abrir un registro desde una grilla: lápiz si puede modificar, ojo si solo consulta.
     Uso: @include('layouts._ico-abrir', ['href' => ..., 'modifica' => bool, 'que' => 'cliente 11']) --}}
<a class="ico-btn" href="{{ $href }}"
   title="{{ $modifica ? 'Editar' : 'Ver' }} {{ $que }}" aria-label="{{ $modifica ? 'Editar' : 'Ver' }} {{ $que }}">
    @if ($modifica)
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
    @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
    @endif
</a>
