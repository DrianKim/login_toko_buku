@extends('owner.layouts.app')

{{-- Notif SweetAlert --}}
@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700">Data User</h1>
            <a href="{{ route('owner.users.create') }}"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-center">
                                <i class="fas fa-cog"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-6 py-3">{{ $user->id }}</td>
                                <td class="px-6 py-3 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-3">{{ $user->email }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $user->role == 'owner'
                                            ? 'bg-purple-100 text-purple-600'
                                            : ($user->role == 'admin'
                                                ? 'bg-red-100 text-red-600'
                                                : ($user->role == 'kasir'
                                                    ? 'bg-green-100 text-green-600'
                                                    : 'bg-gray-100 text-gray-600')) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center flex justify-center gap-3">
                                    <a href="{{ route('owner.users.edit', $user->id) }}"
                                        class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('owner.users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirmDelete(event, '{{ $user->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{-- {{ $users->links('vendor.pagination.usercustom') }} --}}
                <div class="flex items-center justify-between p-4 bg-[#f0f6fb] rounded">
                    {{-- Kiri: info data --}}
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data
                    </div>

                    {{-- Kanan: pagination --}}
                    <nav class="flex items-center gap-1">
                        {{-- Previous Button --}}
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-2.5 py-1 rounded-md border text-sm transition
                                {{ $users->onFirstPage()
                                    ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed pointer-events-none'
                                    : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        @php
                            $current = $users->currentPage();
                            $last = $users->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                            if ($current <= 3) $end = min(5, $last);
                            if ($current >= $last - 2) $start = max(1, $last - 4);
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $users->url(1) }}"
                                class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">1</a>
                            @if ($start > 2)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $current)
                                <span class="px-2.5 py-1 rounded-md border bg-blue-600 text-white border-blue-600 text-sm">{{ $i }}</span>
                            @else
                                <a href="{{ $users->url($i) }}"
                                    class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $users->url($last) }}"
                                class="px-2.5 py-1 rounded-md border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 text-sm">{{ $last }}</a>
                        @endif

                        {{-- Next Button --}}
                        <a href="{{ $users->nextPageUrl() }}"
                            class="px-2.5 py-1 rounded-md border text-sm transition
                                {{ $users->hasMorePages()
                                    ? 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50'
                                    : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed pointer-events-none' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event, name) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: `Hapus user "${name}"?`,
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
