<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Buku')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AlpineJS -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-data="{ openSidebar: true, dropdown: false }" class="bg-gray-50 text-gray-800 font-sans">

    <!-- Overlay untuk mobile -->
    <div x-show="openSidebar"
        @click="openSidebar = false"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside x-cloak
        :class="openSidebar ? 'translate-x-0' : '-translate-x-full'"
        class="fixed left-0 top-0 w-64 h-full bg-white border-r shadow-lg flex flex-col p-4 transform transition-transform duration-300 z-50">

        <!-- Logo -->
        <div class="flex items-center justify-center mb-8">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-book text-white text-xl"></i>
            </div>
            <span class="ml-2 font-bold text-xl text-indigo-600 italic">TokoBuku</span>
        </div>

        <!-- User Info -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-3 mb-6">
            <p class="text-xs text-gray-600 mb-1">Selamat datang,</p>
            <p class="font-bold text-indigo-700">{{ Auth::user()->name ?? 'Guest' }}</p>
        </div>

        <!-- Nav -->
        <nav class="flex flex-col gap-2 mb-auto text-sm">
            <a href="{{ route('owner') }}" class="nav-link {{ request()->routeIs('owner') ? 'active text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="{{ route('owner.data-buku') }}" class="nav-link {{ request()->routeIs('owner.data-buku') ? 'active text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-book"></i> Data Buku
            </a>
            <a href="{{ route('owner.users.index') }}" class="nav-link {{ request()->routeIs('owner.users.*') ? 'active text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-users"></i> User Management
            </a>
            <a href="{{ route('owner.laporan') }}" class="nav-link {{ request()->routeIs('owner.laporan*') ? 'active text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-file-invoice-dollar"></i> Laporan
            </a>
        </nav>

        <!-- Logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <button type="button"
            class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-lg text-sm font-medium transition logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
    </aside>

    <!-- Main Content -->
    <div :class="openSidebar ? 'lg:ml-64' : 'ml-0'" class="min-h-screen flex flex-col transition-all duration-300">

        <!-- Navbar -->
        <header class="flex items-center justify-between bg-white border-b shadow-sm px-6 h-16 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button @click="openSidebar = !openSidebar" class="text-gray-600 hover:text-indigo-600 transition cursor-pointer">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-sm lg:text-base font-semibold text-gray-700" id="date-time"></h1>
            </div>

            <div class="flex items-center gap-4 relative">
                <!-- Notification -->
                {{-- <button class="relative text-gray-600 hover:text-indigo-600">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs flex items-center justify-center rounded-full">3</span>
                </button> --}}

                <!-- Profile Dropdown -->
                <div class="relative" @click.away="dropdown = false">
                    <button @click="dropdown = !dropdown" class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fa-solid fa-user text-indigo-600"></i>
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'User' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <div x-show="dropdown" x-transition class="absolute right-0 mt-2 w-44 bg-white border rounded-lg shadow-lg overflow-hidden">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fa-solid fa-user mr-2"></i> Profil Saya
                        </a>
                        <button type="button" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition logout-btn">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Script -->
    <script>
        // Real-time clock
        function updateTime() {
            const now = new Date();
            const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            document.getElementById('date-time').innerText =
                `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // SweetAlert Logout Confirm
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
