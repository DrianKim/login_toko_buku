@extends('admin.layouts.app')

{{-- SweetAlert Notif --}}
@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", () => {
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
        document.addEventListener("DOMContentLoaded", () => {
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
<div class="max-w-5xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-blue-700">Tambah Detail Buku</h1>
        <a href="{{ route('admin.detail-buku') }}"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-8">
        <form action="{{ route('admin.detail-buku.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Dropdown Buku --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Pilih Buku</label>
                <select id="bukuSelect" name="id_buku"
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" required>
                    <option value="">-- Pilih Judul Buku --</option>
                    @foreach ($buku as $item)
                        <option value="{{ $item->id }}"
                            data-kode="{{ $item->kode_buku }}"
                            data-judul="{{ $item->judul_buku }}"
                            data-tahun="{{ $item->tahun_terbit }}"
                            data-stok="{{ $item->stok ?? 0 }}"
                            data-harga="{{ $item->harga ?? 0 }}">
                            {{ $item->judul_buku }}
                        </option>
                    @endforeach
                </select>
                @error('id_buku')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Stok & Harga --}}
            <div id="detailInputs" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stok" id="inputStok"
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                        placeholder="Masukkan stok" value="{{ old('stok') }}" required>
                    @error('stok')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Harga</label>
                    <input type="number" name="harga" id="inputHarga"
                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                        placeholder="Masukkan harga" value="{{ old('harga') }}" required>
                    @error('harga')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tabel Preview --}}
            <div id="bukuDetailTable" class="hidden">
                <h3 class="text-lg font-semibold text-blue-700 mb-3 mt-4">Preview Detail Buku</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-blue-100 rounded-lg overflow-hidden">
                        <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-center">#</th>
                                <th class="px-6 py-3 text-center">Kode Buku</th>
                                <th class="px-6 py-3">Judul Buku</th>
                                <th class="px-6 py-3 text-center">Stok</th>
                                <th class="px-6 py-3 text-center">Harga</th>
                                <th class="px-6 py-3 text-center">Tahun Terbit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-6 py-3 text-center font-semibold">1</td>
                                <td class="px-6 py-3 text-center font-semibold" id="tableKode"></td>
                                <td class="px-6 py-3" id="tableJudul"></td>
                                <td class="px-6 py-3 text-center" id="tableStok"></td>
                                <td class="px-6 py-3 text-center" id="tableHarga"></td>
                                <td class="px-6 py-3 text-center" id="tableTahun"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script Buku Preview --}}
<script>
    const bukuSelect = document.getElementById('bukuSelect');
    const detailInputs = document.getElementById('detailInputs');
    const bukuDetailTable = document.getElementById('bukuDetailTable');
    const inputStok = document.getElementById('inputStok');
    const inputHarga = document.getElementById('inputHarga');

    const tableKode = document.getElementById('tableKode');
    const tableJudul = document.getElementById('tableJudul');
    const tableStok = document.getElementById('tableStok');
    const tableHarga = document.getElementById('tableHarga');
    const tableTahun = document.getElementById('tableTahun');

    bukuSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];

        if (this.value) {
            detailInputs.classList.remove('hidden');
            bukuDetailTable.classList.remove('hidden');

            // Isi preview tabel
            tableKode.textContent = selected.dataset.kode;
            tableJudul.textContent = selected.dataset.judul;
            tableTahun.textContent = selected.dataset.tahun || '-';
            inputStok.value = selected.dataset.stok || 0;
            inputHarga.value = selected.dataset.harga || 0;

            updateTable();
        } else {
            detailInputs.classList.add('hidden');
            bukuDetailTable.classList.add('hidden');
        }
    });

    function updateTable() {
        tableStok.textContent = inputStok.value;
        tableHarga.textContent = inputHarga.value;
    }

    inputStok.addEventListener('input', updateTable);
    inputHarga.addEventListener('input', updateTable);
</script>
@endsection
