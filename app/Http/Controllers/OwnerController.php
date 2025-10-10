<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataBuku;
use Illuminate\Http\Request;

class OwnerController extends Controller
{

    public function index()
    {
        return view('owner.index');
    }

    public function dataBuku()
    {
        $data = [
            'data_buku' => DataBuku::with(['Tbkategori', 'Tbdetail'])->paginate(10),
        ];

        return view('owner.buku.index', $data);
    }

    public function indexUser()
    {
        $data = [
            'users' => User::orderByRaw("FIELD(role, 'owner', 'admin', 'kasir')")->paginate(10),
        ];
        return view('owner.users.index', $data);
    }

    public function createUser()
    {
        return view('owner.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kasir',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role tidak valid.',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('owner.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $data = [
            'user' => User::findOrFail($id),
        ];

        return view('owner.users.edit', $data);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role tidak valid.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('owner.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('owner.users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('owner.users.index')->with('success', 'User berhasil dihapus.');
    }
}
