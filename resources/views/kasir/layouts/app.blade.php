<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Toko Buku | Kasir</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r shadow-sm flex flex-col p-4 fixed inset-y-0">
            <h2 class="text-lg font-semibold flex items-center justify-center border-b pb-3 mb-4">
                <i class="fa-solid fa-cash-register mr-2 text-indigo-600"></i>
                Kasir Panel
            </h2>

            <p class="text-sm mb-6 text-center">
                Hai, <span class="font-bold text-indigo-600">{{ Auth()->user()->name }}</span> 👋
            </p>

            <nav class="flex flex-col gap-2 mb-auto">
                {{-- Dashboard --}}
                <a href="{{ route('kasir') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition
                        {{ request()->routeIs('kasir') ? 'text-blue-600 font-semibold bg-indigo-50' : 'hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-gauge-high mr-3"></i> Dashboard
                </a>

                {{-- Transaksi --}}
                <a href="{{ route('kasir.transaksi') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition
                        {{ request()->routeIs('kasir.transaksi*') ? 'text-blue-600 font-semibold bg-indigo-50' : 'hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-cash-register mr-3"></i> Transaksi
                </a>

                {{-- Riwayat Transaksi --}}
                <a href="{{ route('kasir.riwayat') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition
                        {{ request()->routeIs('kasir.riwayat*') ? 'text-blue-600 font-semibold bg-indigo-50' : 'hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-clock-rotate-left mr-3"></i> Riwayat Transaksi
                </a>
            </nav>

            {{-- Logout --}}
            <form id="logoutForm" action="/logout" method="POST" class="mt-auto">
                @csrf
                <button type="button" id="logoutBtn"
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm font-medium transition">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                </button>
            </form>
        </aside>

        <!-- Content -->
        <main class="flex-1 ml-64 p-6">
            @yield('content')
        </main>

    </div>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin ingin logout?',
                text: "Anda akan keluar dari sesi kasir saat ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>
</body>

</html>
