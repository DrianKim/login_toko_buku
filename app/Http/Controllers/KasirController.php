<?php

namespace App\Http\Controllers;

use App\Models\DataBuku;
use App\Models\Transaksi;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use App\Models\TransaksiDetail;

class KasirController extends Controller
{
    public function dashboard()
    {
        return view('kasir.index');
    }

    public function indexbuku(Request $request)
    {
        $query = DataBuku::with(['Tbkategori', 'Tbdetail']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul_buku', 'like', "%$q%")
                    ->orWhere('kode_buku', 'like', "%$q%")
                    ->orWhere('penerbit', 'like', "%$q%")
                    ->orWhere('pengarang', 'like', "%$q%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $data = [
            'buku' => $query->latest()->paginate(10),
            'title' => 'Data Buku',
            'Tbkategori' => KategoriBuku::all()->groupBy('kategori'),
        ];

        return view('kasir.buku.index', $data);
    }

    public function indexTransaksi()
    {
        return view('kasir.transaksi.index');
    }

    // Tambah buku ke cart
    public function addToCart(DataBuku $buku)
    {
        if (!$buku || $buku->stok <= 0 || $buku->harga <= 0) {
            return back()->with('error', 'Buku tidak tersedia.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            if ($cart[$buku->id]['qty'] + 1 > $buku->stok) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart[$buku->id]['qty']++;
        } else {
            $cart[$buku->id] = [
                'judul_buku' => $buku->judul_buku,
                'harga' => $buku->harga,
                'qty' => 1,
                'stok' => $buku->stok,
                'cover_buku' => $buku->cover_buku,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang.');
    }

    // Update qty cart
    public function updateQty(Request $request, DataBuku $buku)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$buku->id])) return back();

        $newQty = (int) $request->qty;

        if ($newQty > $buku->stok) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        if ($newQty <= 0) {
            unset($cart[$buku->id]);
        } else {
            $cart[$buku->id]['qty'] = $newQty;
            $cart[$buku->id]['stok'] = $buku->stok;
        }

        session()->put('cart', $cart);

        return back();
    }

    // Hapus item dari cart
    public function removeFromCart($buku_id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$buku_id])) {
            unset($cart[$buku_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Buku berhasil dihapus dari keranjang.');
    }

    // Checkout / simpan transaksi
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) return back()->with('error', 'Keranjang kosong.');

        // Validasi stok & harga
        foreach ($cart as $buku_id => $item) {
            $buku = DataBuku::find($buku_id);
            if (!$buku) return back()->with('error', 'Buku tidak ditemukan.');
            if ($buku->stok < $item['qty']) return back()->with('error', "Stok {$buku->judul_buku} tidak mencukupi.");
            if ($buku->harga <= 0) return back()->with('error', "Buku {$buku->judul_buku} belum memiliki harga.");
        }

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        $diskon = preg_replace('/[^0-9]/', '', $request->diskon ?? 0);
        $diskon = (float)$diskon;
        $subtotal = $total - $diskon;
        $dibayar = preg_replace('/[^0-9]/', '', $request->dibayar ?? 0);
        $dibayar = (float)$dibayar;
        $kembalian = $dibayar - $subtotal;

        if ($request->metode_bayar === 'cash' && $dibayar < $subtotal) {
            return back()->with('error', 'Uang dibayar kurang dari subtotal.');
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'kasir_id' => auth()->id(),
            'total_harga' => $total,
            'diskon' => $diskon,
            'subtotal' => $subtotal,
            'dibayar' => $request->metode_bayar === 'cash' ? $dibayar : null,
            'kembalian' => $request->metode_bayar === 'cash' ? $kembalian : null,
            'metode_bayar' => $request->metode_bayar,
        ]);

        // Simpan detail transaksi & kurangi stok
        foreach ($cart as $buku_id => $item) {
            $buku = DataBuku::find($buku_id);
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'buku_id' => $buku_id,
                'qty' => $item['qty'],
                'harga_satuan' => $buku->harga,
                'subtotal' => $item['qty'] * $buku->harga,
            ]);

            $buku->stok -= $item['qty'];
            $buku->save();
        }

        session()->forget('cart');

        return redirect()->route('kasir.transaksi.struk', $transaksi->id)
            ->with('success', 'Transaksi berhasil.');
    }

    // Tampilkan struk
    public function struk($id)
    {
        $transaksi = Transaksi::with('items.buku')->findOrFail($id);
        return view('kasir.transaksi.struk', compact('transaksi'));
    }

    public function riwayatTransaksi()
    {
        return view('kasir.riwayat.index');
    }
}
