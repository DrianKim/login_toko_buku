@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Kategori Buku</h1>
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('admin.kategori.create') }}" method="GET">
            <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
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
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center" style="width: 13%;"><i class="fa-solid fa-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategori_buku as $Kbuku)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $Kbuku->kategori }}</td>
                        <td>{{ $Kbuku->jenis }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.kategori.edit', $Kbuku->id) }}" class="btn btn-sm btn-warning mb-1" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.kategori.destroy', $Kbuku->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')">
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
