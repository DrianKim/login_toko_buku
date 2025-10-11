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
        Tambah Stok & Harga Buku
    </h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
        <form action="{{ route('admin.detail-buku.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Pilih Buku yang Belum Punya Detail --}}
            <div>
                <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Buku
                </label>
                <select name="buku_id" id="buku_id"
                    class="w-full border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    required>
                    <option hidden value="">-- Pilih Buku --</option>
                    @foreach ($buku as $item)
                        <option value="{{ $item->id }}">{{ $item->judul_buku }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Stok --}}
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                    Stok
                </label>
                <input type="number" id="stok" name="stok" value="{{ old('stok') }}" min="0"
                    placeholder="Masukkan jumlah stok..."
                    class="w-full border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            {{-- Harga --}}
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga
                </label>
                <input type="number" id="harga" name="harga" value="{{ old('harga') }}" min="0"
                    placeholder="Masukkan harga buku..."
                    class="w-full border border-blue-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end space-x-4 pt-4 border-t border-blue-100">
                <a href="{{ route('admin.detail-buku') }}"
                    class="px-5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
