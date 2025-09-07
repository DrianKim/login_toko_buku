<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 col-lg-2 d-md-block bg-dark text-white p-3 position-fixed h-100">
                <div class="d-flex flex-column h-100">
                    <h2 class="h5 text-center mb-4 border-bottom pb-2">
                        <i class="fa-solid fa-user-gear me-2"></i> Admin Panel
                    </h2>
                    <p class="px-2">Hai, <strong>{{ Auth()->user()->name }}</strong> 👋</p>

                    <nav class="nav flex-column mb-auto">
                        <a href="{{ route('admin') }}" class="nav-link text-white mb-2">
                            <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.data-buku') }}" class="nav-link text-white mb-2">
                            <i class="fa-solid fa-book me-2"></i> Data Buku
                        </a>
                        <a href="{{ route('admin.kategori-buku') }}" class="nav-link text-white mb-2">
                            <i class="fa-solid fa-tags me-2"></i> Kategori Buku
                        </a>
                        <a href="{{ route('admin.detail-buku') }}" class="nav-link text-white mb-2">
                            <i class="fa-solid fa-book-open me-2"></i> Detail Buku
                        </a>
                    </nav>

                    <form action="/logout" method="POST" class="mt-auto">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger w-100" title="Logout"
                            onclick="return confirm('Yakin ingin logout?')">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Content -->
            <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>


</html>
