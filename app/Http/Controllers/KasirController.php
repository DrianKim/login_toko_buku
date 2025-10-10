<?php

namespace App\Http\Controllers;

use App\Models\DataBuku;
use App\Models\DetailBuku;
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

    /**
     * Tampilkan halaman keranjang
     */

    public function indexBuku(Request $request)
    {
        $title = 'Data Buku';
        $query = DataBuku::with(['Tbkategori', 'Tbdetail']);
        $kategori = KategoriBuku::all()->groupBy('kategori');

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

        $buku = $query->latest()->paginate(10);

        return view('kasir.buku.index', compact('buku', 'title', 'kategori'));
    }
    public function indexTransaksi()
    {
        $cart = session()->get('cart', []);

        // ✅ Update stok dari database untuk memastikan data terkini
        foreach ($cart as $buku_id => $item) {
            $stokHarga = DetailBuku::where('buku_id', $buku_id)->first();
            if ($stokHarga) {
                $cart[$buku_id]['stok'] = $stokHarga->stok;

                // Kurangi qty jika melebihi stok
                if ($item['qty'] > $stokHarga->stok) {
                    $cart[$buku_id]['qty'] = $stokHarga->stok;
                }
            }
        }

        session()->put('cart', $cart);

        return view('kasir.transaksi.index', compact('cart'));
    }

    /**
     * Tambah buku ke keranjang
     */
    public function addToCart(DataBuku $buku)
    {
        $stokHarga = $buku->stokHarga;

        if (!$stokHarga || $stokHarga->stok <= 0 || $stokHarga->harga <= 0) {
            return back()->with('error', 'Buku ini tidak tersedia untuk dijual.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            // Cek stok sebelum nambah qty
            if ($cart[$buku->id]['qty'] + 1 > $stokHarga->stok) {
                return back()->with('error', 'Stok tidak mencukupi untuk buku ini.');
            }
            $cart[$buku->id]['qty']++;
        } else {
            $cart[$buku->id] = [
                'judul_buku' => $buku->judul_buku,
                'harga'      => $stokHarga->harga,
                'qty'        => 1,
                'stok'       => $stokHarga->stok,
                'cover_buku' => $buku->cover_buku ?? null,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang.'); // ← Perbaiki juga ini
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart($buku_id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$buku_id])) {
            unset($cart[$buku_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Buku berhasil dihapus dari keranjang.');
    }

    /**
     * Proses checkout → simpan transaksi ke DB
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        // 🔎 Validasi stok & harga sebelum transaksi dibuat
        foreach ($cart as $buku_id => $item) {
            $stokHarga = DetailBuku::where('buku_id', $buku_id)->first();

            if (!$stokHarga) {
                return back()->with('error', 'Data stok untuk buku tidak ditemukan.');
            }

            if ($stokHarga->stok < $item['qty']) {
                return back()->with('error', 'Stok ' . $stokHarga->buku->judul_buku . ' tidak mencukupi.');
            }

            if ($stokHarga->harga <= 0) {
                return back()->with('error', 'Buku ' . $stokHarga->buku->judul_buku . ' belum memiliki harga.');
            }
        }

        // ✅ Hitung total setelah validasi
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        // Bersihkan input dari karakter non-digit
        $diskon = preg_replace('/[^0-9]/', '', $request->input('diskon', 0));
        $diskon = (float) $diskon;
        $dibayar = preg_replace('/[^0-9]/', '', $request->input('dibayar', 0));
        $dibayar = (float) $dibayar;
        $subtotal = $total - $diskon;
        $kembalian = $dibayar - $subtotal;
        $kembalian = $dibayar - $subtotal;

        if ($dibayar < $subtotal) {
            return back()->with('error', 'Uang dibayar kurang dari subtotal.');
        }

        // ✅ Simpan transaksi
        $transaksi = Transaksi::create([
            'kasir_id'    => auth()->id(),
            'total_harga' => $total,
            'diskon'      => $diskon,
            'subtotal'    => $subtotal,
            'dibayar'     => $dibayar,
            'kembalian'   => $kembalian,
            'metode_bayar' => $request->input('metode_bayar', 'cash'),
        ]);

        // ✅ Simpan detail + kurangi stok
        foreach ($cart as $buku_id => $item) {
            $stokHarga = DetailBuku::where('buku_id', $buku_id)->first();

            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'buku_id'      => $buku_id,
                'qty'          => $item['qty'],
                'harga_satuan' => $stokHarga->harga, // ambil dari DB biar aman
                'subtotal'     => $stokHarga->harga * $item['qty'],
            ]);

            // Kurangi stok
            $stokHarga->stok -= $item['qty'];
            $stokHarga->save();
        }

        // ✅ Kosongkan keranjang
        session()->forget('cart');

        return redirect()->route('kasir.transaksi.struk', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Cetak struk
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with('items.buku')->findOrFail($id);
        return view('kasir.transaksi.struk', compact('transaksi'));
    }
    public function updateQty(Request $request, DataBuku $buku)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            $newQty = (int) $request->qty;

            // ✅ Validasi stok
            $stokHarga = $buku->stokHarga;
            if ($newQty > $stokHarga->stok) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }

            if ($newQty <= 0) {
                // Kalau qty < 1 hapus item
                unset($cart[$buku->id]);
            } else {
                // Update qty normal
                $cart[$buku->id]['qty'] = $newQty;
                $cart[$buku->id]['stok'] = $stokHarga->stok; // ← Update stok juga
            }

            session()->put('cart', $cart);
        }

        return back();
    }
    public function riwayat()
    {
        $transaksis = Transaksi::with(['kasir', 'items.buku'])
            ->latest()
            ->paginate(10);

        return view('kasir.riwayat_transaksi.index', compact('transaksis'));
    }

    // public function indexbuku(Request $request)
    // {
    //     $query = DataBuku::with(['Tbkategori', 'Tbdetail']);

    //     if ($request->filled('q')) {
    //         $q = $request->q;
    //         $query->where(function ($sub) use ($q) {
    //             $sub->where('judul_buku', 'like', "%$q%")
    //                 ->orWhere('kode_buku', 'like', "%$q%")
    //                 ->orWhere('penerbit', 'like', "%$q%")
    //                 ->orWhere('pengarang', 'like', "%$q%");
    //         });
    //     }

    //     if ($request->filled('kategori_id')) {
    //         $query->where('kategori_id', $request->kategori_id);
    //     }

    //     $data = [
    //         'buku' => $query->latest()->paginate(10),
    //         'title' => 'Data Buku',
    //         'Tbkategori' => KategoriBuku::all()->groupBy('kategori'),
    //     ];

    //     return view('kasir.buku.index', $data);
    // }

    // public function indexTransaksi()
    // {
    //     $cart = session()->get('cart', []);
    //     return view('kasir.transaksi.index', compact('cart'));
    // }

    // // Tambah buku ke cart
    // // public function addToCart(Request $request, $id)
    // // {
    // //     $buku = DataBuku::with('Tbdetail')->findOrFail($id);
    // //     $cart = session()->get('cart', []);
    // //     $qty = $request->qty ?? 1;

    // //     $stokSisa = $buku->Tbdetail->stok - $qty;
    // //     if ($stokSisa < 0) {
    // //         return response()->json(['success' => false, 'message' => 'Stok habis!']);
    // //     }

    // //     $cart[$id] = [
    // //         'judul' => $buku->judul_buku,
    // //         'qty' => ($cart[$id]['qty'] ?? 0) + $qty,
    // //         'harga' => $buku->Tbdetail->harga,
    // //     ];

    // //     session()->put('cart', $cart);
    // //     $buku->Tbdetail->update(['stok' => $stokSisa]);

    // //     return response()->json([
    // //         'success' => true,
    // //         'cart' => $cart,
    // //         'newStock' => $stokSisa
    // //     ]);
    // // }

    // public function addToCart(Request $request, $id)
    // {
    //     $buku = DataBuku::with('Tbdetail')->findOrFail($id);
    //     $cart = session()->get('cart', []);
    //     $stok = $buku->Tbdetail->stok ?? 0;
    //     $harga = $buku->Tbdetail->harga ?? 0;

    //     if (isset($cart[$id])) {
    //         if ($cart[$id]['qty'] < $stok) {
    //             $cart[$id]['qty']++;
    //         } else {
    //             return back()->with('error', 'Stok habis!');
    //         }
    //     } else {
    //         $cart[$id] = [
    //             'judul_buku' => $buku->judul_buku,
    //             'harga' => $harga,
    //             'qty' => 1,
    //             'stok' => $stok,
    //             'cover_buku' => $buku->cover_buku
    //         ];
    //     }

    //     session()->put('cart', $cart);

    //     return back()->with('success', 'Buku berhasil dimasukkan ke keranjang!');
    // }

    // // Update qty cart
    // public function updateQty(Request $request, $id)
    // {
    //     $cart = session('cart', []);
    //     $qty = $request->input('qty', 0);

    //     if ($qty > 0) {
    //         $cart[$id]['qty'] = $qty;
    //     } else {
    //         unset($cart[$id]);
    //     }

    //     session(['cart' => $cart]);

    //     return response()->json(['success' => true, 'cart' => $cart]);
    // }

    // // Hapus item dari cart
    // public function removeFromCart($buku_id)
    // {
    //     $cart = session()->get('cart', []);

    //     if (isset($cart[$buku_id])) {
    //         unset($cart[$buku_id]);
    //         session()->put('cart', $cart);
    //     }

    //     return back()->with('success', 'Buku berhasil dihapus dari keranjang.');
    // }

    // // Checkout / simpan transaksi
    // public function checkout(Request $request)
    // {
    //     $cart = session()->get('cart', []);

    //     if (empty($cart)) return back()->with('error', 'Keranjang kosong.');

    //     // Validasi stok & harga
    //     foreach ($cart as $buku_id => $item) {
    //         $buku = DataBuku::find($buku_id);
    //         if (!$buku) return back()->with('error', 'Buku tidak ditemukan.');
    //         if ($buku->stok < $item['qty']) return back()->with('error', "Stok {$buku->judul_buku} tidak mencukupi.");
    //         if ($buku->harga <= 0) return back()->with('error', "Buku {$buku->judul_buku} belum memiliki harga.");
    //     }

    //     $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
    //     $diskon = preg_replace('/[^0-9]/', '', $request->diskon ?? 0);
    //     $diskon = (float)$diskon;
    //     $subtotal = $total - $diskon;
    //     $dibayar = preg_replace('/[^0-9]/', '', $request->dibayar ?? 0);
    //     $dibayar = (float)$dibayar;
    //     $kembalian = $dibayar - $subtotal;

    //     if ($request->metode_bayar === 'cash' && $dibayar < $subtotal) {
    //         return back()->with('error', 'Uang dibayar kurang dari subtotal.');
    //     }

    //     // Simpan transaksi
    //     $transaksi = Transaksi::create([
    //         'kasir_id' => auth()->id(),
    //         'total_harga' => $total,
    //         'diskon' => $diskon,
    //         'subtotal' => $subtotal,
    //         'dibayar' => $request->metode_bayar === 'cash' ? $dibayar : null,
    //         'kembalian' => $request->metode_bayar === 'cash' ? $kembalian : null,
    //         'metode_bayar' => $request->metode_bayar,
    //     ]);

    //     // Simpan detail transaksi & kurangi stok
    //     foreach ($cart as $buku_id => $item) {
    //         $buku = DataBuku::find($buku_id);
    //         TransaksiDetail::create([
    //             'transaksi_id' => $transaksi->id,
    //             'buku_id' => $buku_id,
    //             'qty' => $item['qty'],
    //             'harga_satuan' => $buku->harga,
    //             'subtotal' => $item['qty'] * $buku->harga,
    //         ]);

    //         $buku->stok -= $item['qty'];
    //         $buku->save();
    //     }

    //     session()->forget('cart');

    //     return redirect()->route('kasir.transaksi.struk', $transaksi->id)
    //         ->with('success', 'Transaksi berhasil.');
    // }

    // // Tampilkan struk
    // public function struk($id)
    // {
    //     $transaksi = Transaksi::with('items.buku')->findOrFail($id);
    //     return view('kasir.transaksi.struk', compact('transaksi'));
    // }

    // public function riwayatTransaksi()
    // {
    //     return view('kasir.riwayat.index');
    // }
}
