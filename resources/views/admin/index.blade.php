<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/coba.css') }}">
    <title>Admin</title>
</head>

<body>
    <div class="container">
        @if (session('error'))
            <div style="color: red;">{{ session('error') }}</div>
        @endif
        <h1 style="text-align: center;">Halaman Admin</h1>
        <p>Hai {{ Auth()->user()->name }}!</p>
        <form action="{{ route('admin.data-buku') }}" method="GET">
            <button type="submit" style="margin-bottom: 1rem;">Lihat Data Buku</button>
        </form>
        <form action="{{ route('admin.kategori-buku') }}" method="GET">
            <button type="submit" style="margin-bottom: 1rem;">Lihat Kategori Buku</button>
        </form>
        <form action="{{ route('admin.detail-buku') }}" method="GET">
            <button type="submit" style="margin-bottom: 1rem;">Lihat Detail Buku</button>
        </form>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>

</html>
