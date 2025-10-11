@extends('owner.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">

        <h1 class="text-3xl font-bold mb-6 text-blue-700">Laporan Penjualan</h1>

        {{-- Filter Laporan --}}
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end" method="GET" action="{{ route('admin.laporan') }}">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $start }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ $end }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Kasir</label>
                    <select name="kasir_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm">
                        <option value="">Semua Kasir</option>
                        @foreach ($kasirList as $k)
                            <option value="{{ $k->id }}" {{ $kasirId == $k->id ? 'selected' : '' }}>
                                {{ $k->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-cash-register text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Transaksi</p>
                    <p class="text-2xl font-bold">{{ $totalTransaksi }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                <div class="bg-green-100 text-green-600 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-coins text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Penjualan</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                <div class="bg-yellow-100 text-yellow-600 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Barang Terjual</p>
                    <p class="text-2xl font-bold">{{ $barangTerjual }}</p>
                </div>
            </div>
        </div>

        {{-- Tabel Transaksi --}}
        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center w-12">#</th>
                            <th class="px-6 py-3 text-center">Tanggal</th>
                            <th class="px-6 py-3 text-center">Kasir</th>
                            <th class="px-6 py-3 text-center">Judul Buku</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-center">Harga Satuan</th>
                            <th class="px-6 py-3 text-center">Total</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $i => $t)
                            @foreach ($t->details as $d)
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-center">{{ $i + 1 }}</td>
                                    <td class="px-6 py-3 text-center">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-3 text-center">{{ $t->kasir->name }}</td>
                                    <td class="px-6 py-3 text-center">{{ $d->buku->judul_buku }}</td>
                                    <td class="px-6 py-3 text-center">{{ $d->qty }}</td>
                                    <td class="px-6 py-3 text-center">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-center">Rp
                                        {{ number_format($d->qty * $d->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <a href="{{ route('owner.struk', $t->id) }}"
                                            class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-semibold shadow transition">
                                            Lihat Struk
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol Export / Cetak --}}
        <div class="mt-6 flex gap-4">
            <a href="{{ route('admin.laporan') }}?export=excel"
                class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                Export Excel
            </a>
            <button onclick="window.print()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                Cetak Laporan
            </button>
        </div>

    </div>
@endsection
