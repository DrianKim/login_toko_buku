@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Data Buku</h1>
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('admin.data-buku.create') }}" method="GET">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Buku
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width: 4%;">Id</th>
                    <th class="text-center">Kode Buku</th>
                    <th class="text-center">Judul Buku</th>
                    <th class="text-center">Penerbit</th>
                    <th class="text-center">Pengarang</th>
                    <th class="text-center">Tahun Terbit</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Cover Buku</th>
                    <th class="text-center"><i class="fa-solid fa-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_buku as $buku)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $buku->kode_buku }}</td>
                        <td>{{ $buku->judul_buku }}</td>
                        <td>{{ $buku->penerbit }}</td>
                        <td>{{ $buku->pengarang }}</td>
                        <td class="text-center">{{ $buku->tahun_terbit }}</td>
                        <td class="text-center">{{ $buku->Tbkategori->kategori ?? '-' }}</td>
                        <td class="text-center">
                            @if ($buku->cover_buku)
                                <img src="{{ asset("storage/{$buku->cover_buku}") }}" alt="Cover Buku"
                                    style="max-width: 80px;" class="img-thumbnail">
                            @else
                                <span class="text-muted">No Cover</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.data-buku.edit', $buku->id) }}" class="btn btn-sm btn-warning mb-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.data-buku.destroy', $buku->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
