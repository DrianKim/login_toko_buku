<div class="flex items-center justify-between p-4 bg-[#f0f6fb] rounded">
    {{-- Kiri: info data --}}
    {{-- Kanan: pagination --}}
    <div class="flex items-center space-x-1">
        @if ($users->onFirstPage())
            <span class="px-3 py-1 bg-white text-blue-400 rounded border border-blue-200 cursor-not-allowed">Prev</span>
        @else
            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 bg-white text-blue-600 rounded border border-blue-300 hover:bg-blue-50">Prev</a>
        @endif

        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            @if ($page == $users->currentPage())
                <span class="px-3 py-1 bg-blue-500 text-white rounded border border-blue-500">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 bg-white text-blue-600 rounded border border-blue-300 hover:bg-blue-50">{{ $page }}</a>
            @endif
        @endforeach

        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 bg-white text-blue-600 rounded border border-blue-300 hover:bg-blue-50">Next</a>
        @else
            <span class="px-3 py-1 bg-white text-blue-400 rounded border border-blue-200 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>
