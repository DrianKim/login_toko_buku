@extends('owner.layouts.app')

@section('content')
    <div class="flex justify-center py-6 bg-gray-100 min-h-screen">
        <!-- Wrapper struk -->
        <div id="struk-print" class="bg-white w-[340px] shadow-lg p-4 rounded-lg font-mono text-sm">
            <!-- Header -->
            <div class="text-center border-b border-dashed pb-2 mb-2">
                <h2 class="font-bold text-lg tracking-widest">NESASEN MART</h2>
                <p class="text-xs">Jl. Pendidikan No. 45, Subang</p>
                <p class="text-xs">Telp: 0812-3456-7890</p>
            </div>

            <!-- Info Transaksi -->
            <div class="border-b border-dashed pb-2 mb-2">
                <p>ID Transaksi: <span class="font-semibold">{{ $transaksi->kode ?? '#TRX-' . now()->timestamp }}</span></p>
                <p>Tanggal: <span>{{ $transaksi->tanggal ?? now()->format('d/m/Y H:i') }}</span></p>
                <p>Kasir: <span>{{ $transaksi->kasir->name ?? Auth::user()->name }}</span></p>
            </div>

            <!-- Daftar Barang -->
            <div class="border-b border-dashed pb-2 mb-2">
                @foreach ($transaksi->details as $item)
                    <div class="flex justify-between mb-1">
                        <span>{{ $item->buku->judul_buku }}</span>
                        <span>{{ $item->qty }} x Rp
                            {{ number_format($item->harga_satuan ?? $item->buku->Tbdetail->harga, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="border-b border-dashed pb-2 mb-2 space-y-1">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Diskon</span>
                    <span>Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Dibayar</span>
                    <span>Rp {{ number_format($transaksi->dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span>Kembali</span>
                    <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-xs mt-4 border-t border-dashed pt-2">
                <p>Terima kasih telah berbelanja di</p>
                <p class="font-bold">NESASEN MART</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
            </div>

            <!-- Tombol aksi -->
            <div class="mt-6 flex justify-center gap-3">
                <a href="javascript:history.back()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
                    Kembali
                </a>

                <button onclick="window.print()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    Print Struk
                </button>
            </div>
        </div>
    </div>

    <!-- Style print -->
    <style>
        /* Print only struk */
        @media print {
            body * {
                visibility: hidden;
                /* sembunyiin semua kecuali struk */
            }

            #struk-print,
            #struk-print * {
                visibility: visible;
            }

            #struk-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            button,
            a {
                display: none !important;
                /* sembunyiin tombol */
            }

            .shadow-lg,
            .rounded-lg {
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
@endsection
