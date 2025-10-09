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

        {{-- Pagination (kalau mau diaktifin) --}}
        {{-- <div class="p-4 border-t border-blue-100">
            {{ $data_buku->links() }}
        </div> --}}
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
