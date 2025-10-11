@extends('owner.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">

        <h1 class="text-3xl font-bold mb-6 text-blue-700">Owner Dashboard</h1>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow p-6 rounded-xl flex flex-col items-center">
                <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <i class="fas fa-cash-register text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">Total Transaksi</p>
                <p class="text-2xl font-bold">{{ $totalTransaksi ?? 0 }}</p>
            </div>

            <div class="bg-white shadow p-6 rounded-xl flex flex-col items-center">
                <div class="bg-green-100 text-green-600 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <i class="fas fa-coins text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">Total Penjualan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white shadow p-6 rounded-xl flex flex-col items-center">
                <div class="bg-yellow-100 text-yellow-600 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">Barang Terjual</p>
                <p class="text-2xl font-bold">{{ $barangTerjual ?? 0 }}</p>
            </div>

            <div class="bg-white shadow p-6 rounded-xl flex flex-col items-center">
                <div class="bg-purple-100 text-purple-600 rounded-full w-12 h-12 flex items-center justify-center mb-2">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">Jumlah Kasir</p>
                <p class="text-2xl font-bold">{{ $totalKasir ?? 0 }}</p>
            </div>
        </div>

        {{-- Grafik Penjualan --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Grafik Penjualan Bulanan</h2>
            <canvas id="salesChart" class="w-full h-64"></canvas>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-xl font-semibold mb-4">Transaksi Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-center">Tanggal</th>
                            <th class="px-6 py-3 text-center">Kasir</th>
                            <th class="px-6 py-3 text-center">Total Item</th>
                            <th class="px-6 py-3 text-center">Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentTransaksi ?? [] as $i => $t)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-6 py-3 text-center">{{ $i + 1 }}</td>
                                <td class="px-6 py-3 text-center">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-3 text-center">{{ $t->kasir->name ?? '-' }}</td>
                                <td class="px-6 py-3 text-center">{{ $t->details->sum('qty') }}</td>
                                <td class="px-6 py-3 text-center">Rp
                                    {{ number_format($t->details->sum(fn($d) => $d->qty * $d->harga_satuan), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesLabels ?? []) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($salesData ?? []) !!},
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
