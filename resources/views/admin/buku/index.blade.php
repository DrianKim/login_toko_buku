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
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700">Data Buku</h1>
            <a href="{{ route('admin.data-buku.create') }}"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Buku
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-center">Kode Buku</th>
                            <th class="px-6 py-3">Judul Buku</th>
                            <th class="px-6 py-3">Penerbit</th>
                            <th class="px-6 py-3 text-center">Tahun Terbit</th>
                            <th class="px-6 py-3 text-center">
                                <i class="fas fa-cog"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_buku as $buku)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-6 py-3 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-3 text-center font-semibold text-gray-800">{{ $buku->kode_buku }}</td>
                                <td class="px-6 py-3">{{ $buku->judul_buku }}</td>
                                <td class="px-6 py-3">{{ $buku->penerbit }}</td>
                                <td class="px-6 py-3 text-center">{{ $buku->tahun_terbit }}</td>
                                <td class="px-6 py-3 text-center flex justify-center gap-3">

                                    {{-- Tombol Show --}}
                                    @include('admin.modal.show_buku', ['buku' => $buku])
                                    <button type="button"
                                        class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1"
                                        data-modal-target="showBukuModal{{ $buku->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.data-buku.edit', $buku->id) }}"
                                        class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('admin.data-buku.destroy', $buku->id) }}" method="POST"
                                        onsubmit="return confirmDelete(event, '{{ $buku->judul_buku }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada data buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                <div class="flex items-center justify-between p-4 bg-[#f0f6fb] rounded">
                    {{-- Kiri: info data --}}
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $data_buku->firstItem() }} - {{ $data_buku->lastItem() }} dari
                        {{ $data_buku->total() }} data
                    </div>

                    {{-- Kanan: pagination --}}
                    <nav class="flex items-center gap-1">
                        {{-- Previous Button --}}
                        <a href="{{ $data_buku->previousPageUrl() }}"
                            class="px-2.5 py-1 rounded-md border text-sm transition
                    {{ $data_buku->onFirstPage()
                        ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed pointer-events-none'
                        : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        @php
                            $current = $data_buku->currentPage();
                            $last = $data_buku->lastPage();
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
                            <a href="{{ $data_buku->url(1) }}"
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
                                <a href="{{ $data_buku->url($i) }}"
                                    class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $data_buku->url($last) }}"
                                class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">{{ $last }}</a>
                        @endif

                        {{-- Next Button --}}
                        <a href="{{ $data_buku->nextPageUrl() }}"
                            class="px-2.5 py-1 rounded-md border text-sm transition
                    {{ $data_buku->hasMorePages()
                        ? 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50'
                        : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed pointer-events-none' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Modal + SweetAlert --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-modal-target]').forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.modalTarget);
                    modal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('[data-modal-close]').forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.modalClose);
                    modal.classList.add('hidden');
                });
            });
        });

        function confirmDelete(event, title) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: `Hapus buku "${title}"?`,
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
