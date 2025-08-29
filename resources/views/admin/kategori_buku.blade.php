<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kategori Buku</title>
</head>

<body>
    <h1>Kategori Buku</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
                {{-- <th>Judul Buku</th> --}}
                <th>Kategori</th>
                <th>Jenis</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori_buku as $Kbuku)
                <tr>
                    <td>{{ $Kbuku->id }}</td>
                    {{-- <td>{{ $Kbuku->buku->judul_buku }}</td>  --}}
                    <td>{{ $Kbuku->kategori }}</td>
                    <td>{{ $Kbuku->jenis }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('admin') }}" method="GET">
        <button type="submit" style="margin-top: 1rem;">Kembali</button>
    </form>

</body>

</html>
