@extends('kasir.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">Dashboard Kasir</h1>

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-cash-register text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold">{{ $transaksiHariIni }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                <div class="bg-green-100 text-green-600 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-coins text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Penjualan</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
                <div class="bg-yellow-100 text-yellow-600 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Barang Terjual</p>
                    <p class="text-2xl font-bold">{{ $barangTerjualHariIni }}</p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        {{-- Transaksi Terbaru --}}
<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h2 class="font-bold text-lg mb-4 text-gray-700">Transaksi Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="border-b border-gray-200 text-gray-500">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Kasir</th>
                    <th class="py-2 px-4">Total</th>
                    <th class="py-2 px-4">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksiTerbaru as $trx)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $trx->kode ?? '#TRX-'.$trx->id }}</td>
                        <td class="py-2 px-4">{{ $trx->kasir->name ?? '-' }}</td>
                        <td class="py-2 px-4">Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                        <td class="py-2 px-4">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    </div>
@endsection
