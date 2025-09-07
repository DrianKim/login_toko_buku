@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Tambah Kategori Buku</h1>

    <form action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" id="kategori" name="kategori" class="form-control" required
                        value="{{ old('kategori') }}">
                    @if ($errors->has('kategori'))
                        <div class="text-danger">{{ $errors->first('kategori') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <input type="text" id="jenis" name="jenis" class="form-control" required
                        value="{{ old('jenis') }}">
                    @if ($errors->has('jenis'))
                        <div class="text-danger">{{ $errors->first('jenis') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.kategori-buku') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
