@extends('admin.layouts.app')

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

@section('content')
    <div class="max-w-2xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-blue-700 mb-8 border-b-2 border-blue-200 pb-4">
            Edit Kategori Buku
        </h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
            <form action="{{ route('admin.kategori.update', $kategori_buku->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kategori  --}}
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" id="kategori" name="kategori"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-blue-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('kategori', $kategori_buku->kategori) }}" required>
                    </div>

                    {{-- Jenis  --}}
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                        <input type="text" id="jenis" name="jenis"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-blue-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('jenis', $kategori_buku->jenis) }}" required>
                    </div>
                </div>

                {{-- Tombol  --}}
                <div class="flex justify-end space-x-4 pt-4 border-t border-blue-100">
                    <a href="{{ route('admin.kategori-buku') }}"
                        class="px-5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
