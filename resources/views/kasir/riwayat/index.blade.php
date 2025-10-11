@extends('kasir.layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700">Riwayat Transaksi</h1>
        </div>

        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center w-12">#</th>
                            <th class="px-6 py-3 text-center">Judul Buku</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-center">Total</th>
                            <th class="px-6 py-3 text-center">Subtotal</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $transaksi)
                            @foreach ($transaksi->details as $item)
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-center">{{ $loop->parent->iteration }}</td>
                                    <td class="px-6 py-3 text-center font-semibold text-gray-800">
                                        {{ $item->buku->judul_buku }}</td>
                                    <td class="px-6 py-3 text-center">{{ $item->qty }}</td>
                                    <td class="px-6 py-3 text-center">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-center">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <a href="{{ route('kasir.struk', $transaksi->id) }}"
                                            class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                            <i class="fas fa-eye"></i> Lihat Struk
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada riwayat transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 bg-[#f0f6fb] rounded flex justify-between items-center text-sm text-gray-600">
                <div>
                    Menampilkan {{ $riwayat->firstItem() ?? 0 }} - {{ $riwayat->lastItem() ?? 0 }} dari
                    {{ $riwayat->total() ?? 0 }} transaksi
                </div>
                <div>
                    {{ $riwayat->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
