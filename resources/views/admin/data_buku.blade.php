<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Data Buku</h1>
        <form action="{{ route('admin.data-buku.create') }}" method="GET" class="mb-3">
            <button type="submit" class="btn btn-primary">Tambah Data Buku</button>
        </form>
        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Kode Buku</th>
                        <th>Judul Buku</th>
                        <th>Penerbit</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Kategori</th>
                        <th>Cover Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_buku as $buku)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $buku->kode_buku }}</td>
                            <td>{{ $buku->judul_buku }}</td>
                            <td>{{ $buku->penerbit }}</td>
                            <td>{{ $buku->pengarang }}</td>
                            <td>{{ $buku->tahun_terbit }}</td>
                            <td>{{ $buku->Tbkategori->kategori }}</td>
                            <td class="text-center">
                                @if ($buku->cover_buku)
                                <img src="{{ asset("storage/{$buku->cover_buku}") }}" alt="Cover Buku"
                                style="max-width: 80px;" class="img-thumbnail">
                                @else
                                <span class="text-muted">No Cover</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="
                                {{-- {{ route('admin.data-buku.edit', $buku->id) }} --}}
                                    " class="btn btn-sm btn-warning mb-1">Edit</a>
                                <form action="
                                {{-- {{ route('admin.data-buku.destroy', $buku->id) }} --}}
                                " method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <form action="{{ route('admin') }}" method="GET">
            <button type="submit" class="btn btn-secondary mt-3">Kembali</button>
        </form>
    </div>
</body>

</html>
