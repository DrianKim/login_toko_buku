@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Detail Buku</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width: 4%;">ID</th>
                    <th class="text-center">Judul Buku</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail_buku as $Dbuku)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $Dbuku->buku->judul_buku ?? '-' }}</td>
                        <td class="text-center">{{ $Dbuku->stok }}</td>
                        <td class="text-center">Rp {{ number_format($Dbuku->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
