@extends('admin.layouts.app')

{{-- Notif SweetAlert --}}
@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-blue-700">Data Buku</h1>
            <a href="{{ route('admin.detail-buku.create') }}"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                + Tambah Stok & Harga
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center w-12">#</th>
                            <th class="px-6 py-3 text-center">Judul Buku</th>
                            <th class="px-6 py-3 text-center">Stok</th>
                            <th class="px-6 py-3 text-center">Harga</th>
                            <th class="px-6 py-3 text-center"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detail_buku as $detail)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-6 py-3 text-center">{{ $loop->iteration }}</td>

                                {{-- Judul Buku dari relasi --}}
                                <td class="px-6 py-3 text-center font-semibold text-gray-800">
                                    {{ $detail->buku->judul_buku ?? '-' }}
                                </td>

                                {{-- Stok --}}
                                <td class="px-6 py-3 text-center">{{ $detail->stok ?? '-' }}</td>

                                {{-- Harga --}}
                                <td class="px-6 py-3 text-center">
                                    @if ($detail->harga)
                                        Rp{{ number_format($detail->harga, 0, ',', '.') }}
                                    @else
                                        <span class="text-gray-400 italic">Belum ada harga</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-3 text-center flex justify-center gap-3">
                                    @include('admin.modal.edit_stok', ['buku' => $detail->buku])
                                    <button type="button"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1"
                                        data-modal-target="editStokModal{{ $detail->buku->id }}">
                                        <i class="fas fa-pen"></i> Stok
                                    </button>

                                    @include('admin.modal.edit_detail', ['buku' => $detail->buku])
                                    <button type="button"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1"
                                        data-modal-target="editDetailModal{{ $detail->buku->id }}">
                                        <i class="fas fa-pen"></i> Harga
                                    </button>

                                    {{-- @include('admin.modal.edit_stok', ['buku' => $detail->buku])
                                    <button type="button"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1"
                                        data-modal-target="editStokModal{{ $detail->buku->id }}">
                                        <i class="fas fa-pen"></i> Stok
                                    </button>

                                    @include('admin.modal.edit_harga', ['buku' => $detail->buku])
                                    <button type="button"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1"
                                        data-modal-target="editHargaModal{{ $detail->buku->id }}">
                                        <i class="fas fa-tag"></i> Harga
                                    </button> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada data buku.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div>
                <div class="flex items-center justify-between p-4 bg-[#f0f6fb] rounded">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $detail_buku->firstItem() }} - {{ $detail_buku->lastItem() }} dari
                        {{ $detail_buku->total() }} data
                    </div>

                    <nav class="flex items-center gap-1">
                        {{-- Previous --}}
                        <a href="{{ $detail_buku->previousPageUrl() }}"
                            class="px-2.5 py-1 rounded-md border text-sm transition
                            {{ $detail_buku->onFirstPage()
                                ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed pointer-events-none'
                                : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        @php
                            $current = $detail_buku->currentPage();
                            $last = $detail_buku->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                            if ($current <= 3) {
                                $end = min(5, $last);
                            }
                            if ($current >= $last - 2) {
                                $start = max(1, $last - 4);
                            }
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $detail_buku->url(1) }}"
                                class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">1</a>
                            @if ($start > 2)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $current)
                                <span
                                    class="px-2.5 py-1 rounded-md border bg-blue-600 text-white border-blue-600 text-sm">{{ $i }}</span>
                            @else
                                <a href="{{ $detail_buku->url($i) }}"
                                    class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $detail_buku->url($last) }}"
                                class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">{{ $last }}</a>
                        @endif

                        {{-- Next --}}
                        <a href="{{ $detail_buku->nextPageUrl() }}"
                            class="px-2.5 py-1 rounded-md border text-sm transition
                            {{ $detail_buku->hasMorePages()
                                ? 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50'
                                : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed pointer-events-none' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-modal-target]').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-modal-target');
                document.getElementById(target).classList.remove('hidden');
            });
        });

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>

@endsection
