@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Edit Data Buku</h1>

    <form action="{{ route('admin.data-buku.update', $data_buku->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode Buku</label>
                    <input type="text" id="kode" name="kode_buku" class="form-control" required
                        value="{{ old('kode_buku', $data_buku->kode_buku) }}">
                    @if ($errors->has('kode_buku'))
                        <div class="text-danger">{{ $errors->first('kode_buku') }}</div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" class="form-control" required
                        value="{{ old('penerbit', $data_buku->penerbit) }}">
                    @if ($errors->has('penerbit'))
                        <div class="text-danger">{{ $errors->first('penerbit') }}</div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="tahun" class="form-label">Tahun Terbit</label>
                    <input type="number" id="tahun" name="tahun_terbit" class="form-control" required
                        value="{{ old('tahun_terbit', $data_buku->tahun_terbit) }}">
                    @if ($errors->has('tahun_terbit'))
                        <div class="text-danger">{{ $errors->first('tahun_terbit') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" id="judul" name="judul_buku" class="form-control" required
                        value="{{ old('judul_buku', $data_buku->judul_buku) }}">
                    @if ($errors->has('judul_buku'))
                        <div class="text-danger">{{ $errors->first('judul_buku') }}</div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang" class="form-control" required
                        value="{{ old('pengarang', $data_buku->pengarang) }}">
                    @if ($errors->has('pengarang'))
                        <div class="text-danger">{{ $errors->first('pengarang') }}</div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select id="kategori" name="kategori_id" class="form-select" required>
                        <option value="" hidden>Pilih Kategori</option>
                        @foreach ($kategori_buku as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id', $data_buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->kategori }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('kategori_id'))
                        <div class="text-danger">{{ $errors->first('kategori_id') }}</div>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Cover Buku</label>
                <input type="file" id="cover" name="cover_buku" class="form-control" accept="image/*">
                @if ($errors->has('cover_buku'))
                    <div class="text-danger">{{ $errors->first('cover_buku') }}</div>
                @endif
                @if ($data_buku->cover_buku)
                    <div class="mt-2">
                        <img src="{{ asset("storage/{$data_buku->cover_buku}") }}" alt="Cover Buku"
                            style="max-width: 120px;" class="img-thumbnail">
                    </div>
                @endif
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.data-buku') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
