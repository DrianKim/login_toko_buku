@extends('owner.layouts.app')

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
@endif

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

@section('content')
    <div class="max-w-2xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-blue-700 mb-8 border-b-2 border-blue-200 pb-4">
            Edit User
        </h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
            <form action="{{ route('owner.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru (opsional)
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti password.</p>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>
                    <select id="role" name="role"
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 bg-white text-gray-800
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end space-x-4 pt-4 border-t border-blue-100">
                    <a href="{{ route('owner.users.index') }}"
                        class="px-5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
