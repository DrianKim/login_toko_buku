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
        <h1 class="text-3xl font-bold text-blue-700">Kategori Buku</h1>

        <a href="{{ route('admin.kategori.create') }}"
            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-center w-12">#</th>
                        <th class="px-6 py-3 text-center">Kategori</th>
                        <th class="px-6 py-3 text-center">Jenis</th>
                        <th class="px-6 py-3 text-center">
                            <i class="fas fa-cog"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori_buku as $Kbuku)
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="px-6 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3 text-center font-semibold text-gray-800">{{ $Kbuku->kategori }}</td>
                            <td class="px-6 py-3 text-center">
                                {{ $Kbuku->jenis }}
                            </td>
                            <td class="px-6 py-3 text-center flex justify-center gap-3">

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.kategori.edit', $Kbuku->id) }}"
                                    class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('admin.kategori.destroy', $Kbuku->id) }}" method="POST"
                                    onsubmit="return confirmDelete(event, '{{ $Kbuku->kategori }}')">
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
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                Belum ada data kategori buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination kalau mau --}}
        {{-- <div class="p-4 border-t border-blue-100">
            {{ $kategori_buku->links() }}
        </div> --}}
    </div>
</div>

<script>
    function confirmDelete(event, name) {
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: `Hapus kategori "${name}"?`,
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
