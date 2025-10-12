@extends('kasir.layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-blue-700 mb-6">Riwayat Kasir</h1>

        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-center">Tanggal</th>
                            <th class="px-6 py-3 text-center">Nama Kasir</th>
                            <th class="px-6 py-3 text-center">Total Transaksi</th>
                            <th class="px-6 py-3 text-center">Total Item Terjual</th>
                            <th class="px-6 py-3 text-center">Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kasirData as $i => $data)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-6 py-3 text-center">{{ $i + 1 }}</td>
                                <td class="px-6 py-3 text-center">
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s') }}
                                </td>
                                <td class="px-6 py-3 text-center">{{ auth()->user()->name }}</td>
                                <td class="px-6 py-3 text-center">{{ $data->total_transaksi }}</td>
                                <td class="px-6 py-3 text-center">{{ $data->total_item }}</td>
                                <td class="px-6 py-3 text-center">
                                    Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
