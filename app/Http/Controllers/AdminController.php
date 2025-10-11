<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataBuku;
use App\Models\DetailBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use ReflectionFunctionAbstract;
use PhpParser\Node\Scalar\DNumber;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'jumlahBuku' => DataBuku::count(),
            'jumlahUser' => User::whereIn('role', ['admin', 'kasir'])->count(),
            'jumlahKategori' => KategoriBuku::count(),
            'bukuTerbaru' => DataBuku::with(['Tbkategori', 'Tbdetail'])->latest()->take(5)->get(),
        ];
        return view('admin.dashboard', $data);
    }
    public function dataBuku()
    {
        $data = [
            'data_buku' => DataBuku::with(['Tbkategori', 'Tbdetail'])->paginate(10),
        ];

        return view('admin.buku.index', $data);
    }

    public function createDataBuku()
    {
        $data = [
            'kategori_buku' => KategoriBuku::all(),
        ];
        return view('admin.buku.create', $data);
    }

    public function storeDataBuku(Request $request)
    {
        $validated = $request->validate([
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'cover_buku' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'kategori_id' => 'required|exists:kategori_buku,id',
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'pengarang.required' => 'Pengarang wajib diisi.',
            'penerbit.required' => 'Penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900.',
            'tahun_terbit.max' => 'Tahun terbit maksimal adalah tahun sekarang.',
            'cover_buku.required' => 'Cover buku wajib diunggah.',
            'cover_buku.image' => 'File yang diunggah harus berupa gambar.',
            'cover_buku.mimes' => 'Format gambar harus jpeg, png, jpg, atau svg.',
            'cover_buku.max' => 'Ukuran gambar maksimal 2MB.',
            'kategori_id.required' => 'Kategori buku wajib diisi.',
            'kategori_id.exists' => 'Kategori buku tidak valid.',
        ]);

        $kategori = KategoriBuku::findOrFail($request->kategori_id);

        $prefix = 'BK' . strtoupper(substr($kategori->kategori, 0, 1)) . strtoupper(substr($kategori->jenis, 0, 1));

        $lastBook = DataBuku::where('kode_buku', 'like', $prefix . '%')
            ->orderBy('kode_buku', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastBook) {
            $lastNumber = intval(substr($lastBook->kode_buku, -3));
            $nextNumber = $lastNumber + 1;
        }

        $kodeBuku = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $validated['kode_buku'] = $kodeBuku;

        if ($request->hasFile('cover_buku')) {
            $imageName = time() . '.' . $request->cover_buku->extension();
            $path = $request->file('cover_buku')->storeAs('images', $imageName, 'public');
            $validated['cover_buku'] = $path;
        }

        DataBuku::create($validated);

        return redirect()->route('admin.data-buku')->with('success', 'Data buku berhasil ditambahkan.');
    }


    public function editDataBuku($id)
    {
        $data = [
            'kategori_buku' => KategoriBuku::all(),
            'data_buku' => DataBuku::findOrFail($id),
        ];
        return view('admin.buku.edit', $data);
    }

    public function updateDataBuku(Request $request, $id)
    {
        $buku = DataBuku::findOrFail($id);

        $validated = $request->validate([
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'cover_buku' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'kategori_id' => 'required|exists:kategori_buku,id',
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'pengarang.required' => 'Pengarang wajib diisi.',
            'penerbit.required' => 'Penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900.',
            'tahun_terbit.max' => 'Tahun terbit maksimal adalah tahun sekarang.',
            'cover_buku.image' => 'File yang diunggah harus berupa gambar.',
            'cover_buku.mimes' => 'Format gambar harus jpeg, png, jpg, atau svg.',
            'cover_buku.max' => 'Ukuran gambar maksimal 2MB.',
            'kategori_id.required' => 'Kategori buku wajib diisi.',
            'kategori_id.exists' => 'Kategori buku tidak valid.',
        ]);

        $kategoriBaru = KategoriBuku::findOrFail($request->kategori_id);

        $kategoriLama = $buku->kategori_id ? KategoriBuku::find($buku->kategori_id) : null;
        $kategoriBerubah = !$kategoriLama ||
            ($kategoriLama->kategori !== $kategoriBaru->kategori ||
                $kategoriLama->jenis !== $kategoriBaru->jenis);

        if ($kategoriBerubah) {
            $prefix = 'BK' . strtoupper(substr($kategoriBaru->kategori, 0, 1)) . strtoupper(substr($kategoriBaru->jenis, 0, 1));

            $lastBook = DataBuku::where('kode_buku', 'like', $prefix . '%')
                ->orderBy('kode_buku', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastBook) {
                $lastNumber = intval(substr($lastBook->kode_buku, -3));
                $nextNumber = $lastNumber + 1;
            }

            $kodeBaru = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $validated['kode_buku'] = $kodeBaru;
        } else {
            $validated['kode_buku'] = $buku->kode_buku;
        }

        if ($request->hasFile('cover_buku')) {
            $imageName = time() . '.' . $request->cover_buku->extension();
            $path = $request->file('cover_buku')->storeAs('images', $imageName, 'public');
            $validated['cover_buku'] = $path;
        }

        $buku->update($validated);

        return redirect()->route('admin.data-buku')->with('success', 'Data buku berhasil diperbarui.');
    }


    public function deleteDataBuku($id)
    {
        $buku = DataBuku::findOrFail($id);
        $buku->delete();

        return redirect()->route('admin.data-buku')->with('success', 'Data buku berhasil dihapus.');
    }

    public function kategoriBuku()
    {
        $data = [
            'kategori_buku' => KategoriBuku::paginate(2),
        ];
        return view('admin.kategori.index', $data);
    }

    public function createKategoriBuku()
    {
        return view('admin.kategori.create');
    }

    public function storeKategoriBuku(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
        ], [
            'kategori.required' => 'Kategori wajib diisi.',
            'jenis.required' => 'Jenis wajib diisi.',
        ]);

        KategoriBuku::create($validated);

        return redirect()->route('admin.kategori-buku')->with('success', 'Kategori buku berhasil ditambahkan.');
    }

    public function editKategoriBuku($id)
    {
        $data = [
            'kategori_buku' => KategoriBuku::findOrFail($id),
        ];

        return view('admin.kategori.edit', $data);
    }

    public function updateKategoriBuku(Request $request, $id)
    {
        $kategori = KategoriBuku::findOrFail($id);

        $validated = $request->validate([
            'kategori' => 'required|string|max:255' . $kategori->id,
            'jenis' => 'required|string|max:255',
        ], [
            'kategori.required' => 'Kategori wajib diisi.',
            'kategori.unique' => 'Kategori sudah ada.',
            'jenis.required' => 'Jenis wajib diisi.',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori-buku')->with('success', 'Kategori buku berhasil diperbarui.');
    }

    public function deleteKategoriBuku($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori-buku')->with('success', 'Kategori buku berhasil dihapus.');
    }

    public function detailBuku()
    {
        $data = [
            'detail_buku' => DetailBuku::with('buku')->orderBy('id', 'desc')->paginate(10),
            'buku' => DataBuku::all(),
        ];

        return view('admin.detail.index', $data);
    }

    public function createDetailBuku()
    {
        $data = [
            'buku' => DataBuku::doesntHave('Tbdetail')->get(),
        ];

        return view('admin.detail.create', $data);
    }

    public function storeDetailBuku(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:data_buku,id|unique:detail_buku,buku_id',
            'stok' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        DetailBuku::create([
            'buku_id' => $request->buku_id,
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        return redirect()->route('admin.detail-buku')->with('success', 'Stok & harga berhasil ditambahkan!');
    }

    // public function storeDetailBuku(Request $request)
    // {
    //     $request->validate([
    //         'id_buku' => 'required|exists:data_buku,id',
    //         'stok' => 'required|numeric|min:0',
    //         'harga' => 'required|numeric|min:0',
    //     ]);

    //     $detail = DetailBuku::where('id_buku', $request->id_buku)->first();

    //     if ($detail) {
    //         $detail->stok = $detail->stok + $request->stok;
    //         $detail->harga = $request->harga;
    //         $detail->save();

    //         return redirect()->route('admin.detail-buku')->with('success', 'Stok buku berhasil diperbarui!');
    //     } else {
    //         DetailBuku::create([
    //             'id_buku' => $request->id_buku,
    //             'stok' => $request->stok,
    //             'harga' => $request->harga,
    //         ]);

    //         return redirect()->route('admin.detail-buku')->with('success', 'Detail buku berhasil ditambahkan!');
    //     }
    // }

    public function tambahStok(Request $request, $id)
    {
        $request->validate([
            'stok_baru' => 'required|integer|min:1',
        ]);

        $buku = DataBuku::findOrFail($id);
        $detail = $buku->Tbdetail;

        if ($detail) {
            $detail->stok += $request->stok_baru;
            $detail->save();
        } else {
            DetailBuku::create([
                'buku_id' => $buku->id,
                'stok' => $request->stok_baru,
                'harga' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Stok buku berhasil ditambahkan!');
    }

    // Update harga buku
    public function updateHarga(Request $request, $id)
    {
        $request->validate([
            'harga_baru' => 'required|integer|min:0',
        ]);

        $buku = DataBuku::findOrFail($id);
        $detail = $buku->Tbdetail;

        if ($detail) {
            $detail->update(['harga' => $request->harga_baru]);
        } else {
            DetailBuku::create([
                'buku_id' => $buku->id,
                'stok' => 0,
                'harga' => $request->harga_baru,
            ]);
        }

        return redirect()->back()->with('success', 'Harga buku berhasil diperbarui!');
    }

    public function indexUser()
    {
        $data = [
            'users' => User::where('role', 'kasir')->paginate(10),
        ];
        return view('admin.users.index', $data);
    }

    public function createUser()
    {
        return view('admin.users.create');
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

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $data = [
            'user' => User::findOrFail($id),
        ];

        return view('admin.users.edit', $data);
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

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
