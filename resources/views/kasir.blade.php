<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/coba.css') }}">
    <title>Kasir</title>
</head>

<body>
    <div class="container">
        @if (session('error'))
            <div style="color: red;">{{ session('error') }}</div>
        @endif
        <h1 style="text-align: center;">Halaman Kasir</h1>
        <p>Hai {{ Auth()->user()->name }}!</p>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>

</html>
