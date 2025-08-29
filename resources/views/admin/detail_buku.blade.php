<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Buku</title>
</head>

<body>
    <h1>Detail Buku</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
                <th>Judul Buku</th>
                <th>Stok</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_buku as $Dbuku)
                <tr>
                    <td>{{ $Dbuku->id }}</td>
                    <td>{{ $Dbuku->buku->judul_buku }}</td>
                    <td>{{ $Dbuku->stok }}</td>
                    <td>{{ $Dbuku->harga }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('admin') }}" method="GET">
        <button type="submit" style="margin-top: 1rem;">Kembali</button>
    </form>

</body>

</html>
