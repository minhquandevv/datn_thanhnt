@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Nút về đầu --}}
            @if (!$paginator->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}" title="Trang đầu">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
            @endif

            {{-- Nút trước --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>

            {{-- Số trang --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Nút tiếp --}}
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>

            {{-- Nút cuối --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" title="Trang cuối">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif