@if ($paginator->hasPages())
<nav class="pager" role="navigation" aria-label="Paginación">
    <span>Mostrando {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}</span>
    <ul>
        @if ($paginator->onFirstPage())
            <li><span class="pg dis" aria-disabled="true">‹ Anterior</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Anterior</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li><span class="pg dis">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="pg cur" aria-current="page">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente ›</a></li>
        @else
            <li><span class="pg dis" aria-disabled="true">Siguiente ›</span></li>
        @endif
    </ul>
</nav>
@endif
