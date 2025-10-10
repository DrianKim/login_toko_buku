@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">

    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Jumlah Buku --}}
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
            <div class="bg-blue-100 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-book text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Buku</p>
                <p class="text-2xl font-bold">{{ $jumlahBuku }}</p>
            </div>
        </div>

        {{-- Jumlah User --}}
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
            <div class="bg-green-100 text-green-600 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total User</p>
                <p class="text-2xl font-bold">{{ $jumlahUser }}</p>
            </div>
        </div>

        {{-- Jumlah Kategori --}}
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
            <div class="bg-yellow-100 text-yellow-600 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-tags text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Kategori</p>
                <p class="text-2xl font-bold">{{ $jumlahKategori }}</p>
            </div>
        </div>
    </div>

    {{-- Bawah / Placeholder --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Chart atau Ringkasan --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg mb-4">Ringkasan Terbaru</h2>
            <p class="text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. At rem aliquam, sed consectetur ad soluta neque ipsa facere, reiciendis pariatur consequuntur debitis accusamus enim dicta reprehenderit. Unde quidem animi repellat?</p>
        </div>

        {{-- Tabel terbaru --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-lg mb-4">Buku Terbaru</h2>
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="py-2">Judul</th>
                        <th class="py-2">Kategori</th>
                        <th class="py-2">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bukuTerbaru as $buku)
                        <tr class="border-b border-gray-100">
                            <td class="py-2">{{ $buku->judul_buku ?? '-' }}</td>
                            <td class="py-2">{{ $buku->Tbkategori->kategori ?? '-' }}</td>
                            <td class="py-2">{{ $buku->Tbdetail->stok ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
