<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Data Buku</title>
</head>

<body>
    <h1>Tambah Data Buku</h1>

    <form action="{{ route('admin.data-buku.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="kode">Kode buku</label>
            <input type="text" id="kode" name="kode_buku" required value="{{ old('kode_buku') }}">
            @if ($errors->has('kode_buku'))
                <div style="color: red;">{{ $errors->first('kode_buku') }}</div>
            @endif
        </div>
        <div>
            <label for="judul">Judul buku</label>
            <input type="text" id="judul" name="judul_buku" required value="{{ old('judul_buku') }}">
            @if ($errors->has('judul_buku'))
                <div style="color: red;">{{ $errors->first('judul_buku') }}</div>
            @endif
        </div>
        <div>
            <label for="penerbit">Penerbit</label>
            <input type="text" id="penerbit" name="penerbit" required value="{{ old('penerbit') }}">
            @if ($errors->has('penerbit'))
                <div style="color: red;">{{ $errors->first('penerbit') }}</div>
            @endif
        </div>
        <div>
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="pengarang" required value="{{ old('pengarang') }}">
            @if ($errors->has('pengarang'))
                <div style="color: red;">{{ $errors->first('pengarang') }}</div>
            @endif
        </div>
        <div>
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="tahun_terbit" required value="{{ old('tahun_terbit') }}">
            @if ($errors->has('tahun_terbit'))
                <div style="color: red;">{{ $errors->first('tahun_terbit') }}</div>
            @endif
        </div>
        <div>
            <label for="kategori">Kategori</label>
            <select id="kategori" name="id_kategori" required>
                <option value="" hidden>Pilih Kategori</option>
                @foreach ($kategori_buku as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('id_kategori') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->kategori }}</option>
                @endforeach
            </select>
            @if ($errors->has('id_kategori'))
                <div style="color: red;">{{ $errors->first('id_kategori') }}</div>
            @endif
        <div>
            <label for="cover">Cover Buku</label>
            <input type="file" id="cover" name="cover_buku" accept="image/*" required>
            @if ($errors->has('cover_buku'))
                <div style="color: red;">{{ $errors->first('cover_buku') }}</div>
            @endif
        </div>
        <button type="submit">Simpan</button>
        <a href="{{ route('admin.data-buku') }}">
            <button type="button">Kembali</button>
        </a>
    </form>

</body>

</html>
