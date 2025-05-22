@if ($paginator->hasPages())
<div class="pagination-wrapper">
    <p class="pagination-info">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </p>

    <ul class="pagination">
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}" aria-disabled="{{ $paginator->onFirstPage() ? 'true' : 'false' }}" aria-label="pagination.previous">
            @if ($paginator->onFirstPage())
                <span aria-hidden="true">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
            @endif
        </li>

        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                @if ($page == $paginator->currentPage())
                    <span>{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            </li>
        @endforeach

        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}" aria-disabled="{{ $paginator->hasMorePages() ? 'false' : 'true' }}" aria-label="pagination.next">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
            @else
                <span aria-hidden="true">&rsaquo;</span>
            @endif
        </li>
    </ul>
</div>
@endif
