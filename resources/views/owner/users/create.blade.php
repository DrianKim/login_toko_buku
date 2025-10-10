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

@section('content')
    <div class="max-w-2xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-blue-700 mb-8 border-b-2 border-blue-200 pb-4">
            Tambah User
        </h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
            <form action="{{ route('owner.users.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap..."
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email user..."
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password..."
                        class="w-full border border-blue-200 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
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
                        <option value="" hidden>Pilih role...</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
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
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
