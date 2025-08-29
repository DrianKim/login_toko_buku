<?php

namespace App\Http\Controllers;

use App\Models\DataBuku;
use App\Models\DetailBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\DNumber;
use ReflectionFunctionAbstract;

class AdminController extends Controller
{
    public function dataBuku()
    {
        $data = [
            'data_buku' => DataBuku::with('Tbkategori')->get(),
        ];

        return view('admin.data_buku', $data);
    }

    public function createDataBuku()
    {
        $data = [
            'kategori_buku' => KategoriBuku::all(),
        ];
        return view('admin.create_data_buku', $data);
    }

    public function storeDataBuku(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|string|max:255|unique:data_buku,kode_buku',
            'judul_buku' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'cover_buku' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'id_kategori' => 'required|exists:kategori_buku,id',
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah ada.',
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
            'id_kategori.required' => 'Kategori buku wajib diisi.',
            'id_kategori.exists' => 'Kategori buku tidak valid.',
        ]);

        if ($request->hasFile('cover_buku')) {
            $imageName = time() . '.' . $request->cover_buku->extension();
            $path = $request->file('cover_buku')->storeAs('images', $imageName, 'public');
            $validated['cover_buku'] = $path;
        }

        DataBuku::create($validated);

        return redirect()->route('admin.data-buku')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function kategoriBuku()
    {
        $data = [
            'kategori_buku' => KategoriBuku::with('buku')->get(),
        ];
        return view('admin.kategori_buku', $data);
    }

    public function detailBuku()
    {
        $data = [
            'detail_buku' => DetailBuku::with('buku')->get(),
        ];
        return view('admin.detail_buku', $data);
    }
}
