<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Toko Buku | Kasir</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                <a href="{{ route('kasir.buku') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition
                        {{ request()->routeIs('kasir.buku*') ? 'text-blue-600 font-semibold bg-indigo-50' : 'hover:bg-indigo-50 hover:text-indigo-600' }}">
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
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <button type="button"
                class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-lg text-sm font-medium transition logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </aside>

        <!-- Content -->
        <main class="flex-1 ml-64 p-6">
            @yield('content')
        </main>

    </div>

    <script>
        document.querySelectorAll('.logout-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Yakin mau logout?',
                    text: 'Kamu akan keluar dari sesi ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, keluar',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
