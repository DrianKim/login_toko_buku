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
        <h1 class="text-3xl font-bold text-blue-700 mb-8 border-b-2 border-blue-200 pb-4">Tambah Buku</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
            <form action="{{ route('admin.data-buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Kode Buku --}}
                <div>
                    <label for="kode_buku" class="block text-sm font-medium text-gray-700 mb-2">Kode Buku</label>
                    <input type="text" id="kode_buku" name="kode_buku" readonly
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-blue-50 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Judul Buku --}}
                <div>
                    <label for="judul_buku" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                    <input type="text" id="judul_buku" name="judul_buku"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan judul buku..." required>
                </div>

                {{-- Penerbit --}}
                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan penerbit..." required>
                </div>

                {{-- Pengarang --}}
                <div>
                    <label for="pengarang" class="block text-sm font-medium text-gray-700 mb-2">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan pengarang..." required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="kategori_id" name="kategori_id"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori_buku as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun Terbit --}}
                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan tahun terbit..." required>
                </div>

                {{-- Cover --}}
                <div>
                    <label for="cover_buku" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                    <input type="file" id="cover_buku" name="cover_buku" accept="image/*"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-blue-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end space-x-4 pt-4 border-t border-blue-100">
                    <a href="{{ route('admin.data-buku') }}"
                        class="px-5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                        Simpan
                    </button>
                </div>
            </form>

            <script>
                document.getElementById('kategori_id').addEventListener('change', function() {
                    let kategoriId = this.value;
                    if (kategoriId) {
                        fetch(`/admin/buku/generate-kode/${kategoriId}`)
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('kode_buku').value = data.kode_buku;
                            });
                    } else {
                        document.getElementById('kode_buku').value = '';
                    }
                });
            </script>
        </div>
    </div>
@endsection
