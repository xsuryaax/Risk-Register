@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center">
        <ul class="pagination pagination-rounded mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="fa fa-chevron-left text-xs"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fa fa-chevron-left text-xs"></i></a>
                </li>
            @endif

            {{-- Logic for limited 3 page numbers around current --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                
                // Show current-1, current, current+1
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
                
                // Adjust if at start or end to maintain 3 numbers if possible
                if ($currentPage == 1) {
                    $end = min($lastPage, 3);
                } elseif ($currentPage == $lastPage) {
                    $start = max(1, $lastPage - 2);
                }
            @endphp

            {{-- First Page / Ellipsis --}}
            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link border-0">...</span></li>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            {{-- Last Page / Ellipsis --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link border-0">...</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa fa-chevron-left fa-rotate-180 text-xs"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="fa fa-chevron-left fa-rotate-180 text-xs"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
