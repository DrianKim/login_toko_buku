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
    <div class="max-w-3xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-blue-700 mb-8 border-b-2 border-blue-200 pb-4">Edit Data Buku</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
            <form action="{{ route('admin.data-buku.update', $data_buku->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kode Buku --}}
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode Buku</label>
                        <input type="text" id="kode" name="kode_buku"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-blue-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('kode_buku', $data_buku->kode_buku) }}" required readonly>
                    </div>

                    {{-- Judul Buku --}}
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                        <input type="text" id="judul" name="judul_buku"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('judul_buku', $data_buku->judul_buku) }}" required>
                    </div>

                    {{-- Penerbit --}}
                    <div>
                        <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('penerbit', $data_buku->penerbit) }}" required>
                    </div>

                    {{-- Pengarang --}}
                    <div>
                        <label for="pengarang" class="block text-sm font-medium text-gray-700 mb-2">Pengarang</label>
                        <input type="text" id="pengarang" name="pengarang"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('pengarang', $data_buku->pengarang) }}" required>
                    </div>

                    {{-- Tahun Terbit --}}
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                        <input type="number" id="tahun" name="tahun_terbit"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('tahun_terbit', $data_buku->tahun_terbit) }}" required>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select id="kategori" name="kategori_id"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" hidden>Pilih Kategori</option>
                            @foreach ($kategori_buku as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ old('kategori_id', $data_buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Cover Buku --}}
                <div>
                    <label for="cover" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                    <input type="file" id="cover" name="cover_buku" accept="image/*"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-blue-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if ($data_buku->cover_buku)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">Preview cover saat ini:</p>
                            <img src="{{ asset("storage/{$data_buku->cover_buku}") }}" alt="Cover Buku"
                                class="rounded-lg border border-blue-200 shadow-sm w-32 h-auto">
                        </div>
                    @endif
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end space-x-4 pt-4 border-t border-blue-100">
                    <a href="{{ route('admin.data-buku') }}"
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
