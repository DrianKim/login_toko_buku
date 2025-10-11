<?php

namespace App\Http\Controllers;

use App\Models\DataBuku;
use App\Models\Transaksi;
use App\Models\DetailBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KasirController extends Controller
{
    public function dashboard()
    {
        $transaksiHariIni = Transaksi::whereDate('created_at', now())->count();
        $totalPenjualanHariIni = Transaksi::whereDate('created_at', now())->sum('total_harga');
        $barangTerjualHariIni = TransaksiDetail::whereHas('transaksi', function ($q) {
            $q->whereDate('created_at', now());
        })->sum('qty');
        $transaksiTerbaru = Transaksi::with('kasir')
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'transaksiHariIni',
            'totalPenjualanHariIni',
            'barangTerjualHariIni',
            'transaksiTerbaru'
        ));
    }



    // // Halaman utama kasir
    // public function index()
    // {
    //     $buku = DataBuku::with('Tbdetail')->get();
    //     $keranjang = Session::get('keranjang', []);
    //     $total = collect($keranjang)->sum(fn($item) => $item['subtotal']);

    //     return view('kasir.buku.index', compact('buku', 'keranjang', 'total'));
    // }

    // // Ambil data keranjang (buat AJAX update)
    // public function getKeranjang()
    // {
    //     $keranjang = Session::get('keranjang', []);
    //     $total = collect($keranjang)->sum(fn($item) => $item['subtotal']);

    //     return response()->json([
    //         'keranjang' => $keranjang,
    //         'total' => $total,
    //     ]);
    // }

    // // Tambah item ke keranjang
    // public function tambahKeranjang(Request $request)
    // {
    //     $buku = DataBuku::with('Tbdetail')->find($request->buku_id);
    //     if (!$buku) {
    //         return response()->json(['error' => 'Buku tidak ditemukan.'], 404);
    //     }

    //     $keranjang = Session::get('keranjang', []);

    //     if (isset($keranjang[$buku->id])) {
    //         $keranjang[$buku->id]['qty'] += 1;
    //     } else {
    //         $keranjang[$buku->id] = [
    //             'id' => $buku->id,
    //             'judul' => $buku->judul_buku,
    //             'harga' => $buku->Tbdetail->harga ?? 0,
    //             'qty' => 1,
    //             'subtotal' => $buku->Tbdetail->harga ?? 0,
    //         ];
    //     }

    //     // Update subtotal tiap item
    //     $keranjang[$buku->id]['subtotal'] = $keranjang[$buku->id]['harga'] * $keranjang[$buku->id]['qty'];

    //     Session::put('keranjang', $keranjang);

    //     return response()->json(['success' => true, 'keranjang' => $keranjang]);
    // }

    // // Update jumlah barang di keranjang
    // public function updateKeranjang(Request $request)
    // {
    //     $keranjang = Session::get('keranjang', []);
    //     $id = $request->buku_id;

    //     if (isset($keranjang[$id])) {
    //         $keranjang[$id]['qty'] = max(1, $request->qty);
    //         $keranjang[$id]['subtotal'] = $keranjang[$id]['harga'] * $keranjang[$id]['qty'];
    //         Session::put('keranjang', $keranjang);
    //     }

    //     return response()->json(['success' => true, 'keranjang' => $keranjang]);
    // }

    // // Hapus item dari keranjang
    // public function hapusKeranjang(Request $request)
    // {
    //     $keranjang = Session::get('keranjang', []);
    //     unset($keranjang[$request->buku_id]);
    //     Session::put('keranjang', $keranjang);

    //     return response()->json(['success' => true, 'keranjang' => $keranjang]);
    // }

    // // Checkout transaksi
    // public function checkout(Request $request)
    // {
    //     $keranjang = Session::get('keranjang', []);
    //     if (empty($keranjang)) {
    //         return redirect()->back()->with('error', 'Keranjang masih kosong!');
    //     }

    //     $total = collect($keranjang)->sum(fn($item) => $item['subtotal']);
    //     $diskon = $request->diskon ?? 0;
    //     $subtotal = $total - $diskon;
    //     $dibayar = $request->dibayar;
    //     $kembalian = $dibayar - $subtotal;

    //     // Simpan transaksi
    //     $transaksi = Transaksi::create([
    //         'kasir_id' => Auth::id(),
    //         'total_harga' => $total,
    //         'diskon' => $diskon,
    //         'subtotal' => $subtotal,
    //         'dibayar' => $dibayar,
    //         'kembalian' => $kembalian,
    //         'metode_bayar' => $request->metode_bayar,
    //     ]);

    //     // Simpan detail transaksi
    //     foreach ($keranjang as $item) {
    //         TransaksiDetail::create([
    //             'transaksi_id' => $transaksi->id,
    //             'buku_id' => $item['id'],
    //             'qty' => $item['qty'],
    //             'harga_satuan' => $item['harga'],
    //             'subtotal' => $item['subtotal'],
    //         ]);

    //         // Kurangi stok
    //         $detail = DetailBuku::where('buku_id', $item['id'])->first();
    //         if ($detail) {
    //             $detail->stok -= $item['qty'];
    //             $detail->save();
    //         }
    //     }

    //     Session::forget('keranjang');

    //     return redirect()->route('kasir.struk', $transaksi->id)->with('success', 'Transaksi berhasil!');
    // }

    // // Cetak struk
    // public function struk($id)
    // {
    //     $transaksi = Transaksi::with(['details.transaksi', 'details.transaksi.kasir', 'details.transaksi.kasir'])
    //         ->with(['details' => function ($q) {
    //             $q->with('transaksi');
    //         }])
    //         ->findOrFail($id);

    //     return view('kasir.struk', compact('transaksi'));
    // }

    public function index(Request $request)
    {
        $query = DataBuku::with('Tbdetail', 'Tbkategori');

        if ($request->q) {
            $query->where('judul_buku', 'like', '%' . $request->q . '%')
                ->orWhere('kode_buku', 'like', '%' . $request->q . '%');
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->get();
        $kategori = KategoriBuku::all()->groupBy('kategori');

        // pastikan session key 'keranjang' konsisten
        $keranjang = Session::get('keranjang', []);

        return view('kasir.buku.index', compact('buku', 'kategori', 'keranjang'));
    }

    // Tambah buku ke keranjang (route: kasir.keranjang.tambah)
    public function tambahKeranjang(Request $r)
    {
        $r->validate([
            'buku_id' => 'required|integer|exists:data_buku,id',
            'qty' => 'nullable|integer|min:1'
        ]);

        $buku = DataBuku::with('Tbdetail')->findOrFail($r->buku_id);
        $dbStok = (int) ($buku->Tbdetail->stok ?? 0);
        $qtyAdd = (int) ($r->qty ?? 1);

        $keranjang = Session::get('keranjang', []);
        $currentQty = isset($keranjang[$buku->id]) ? (int)$keranjang[$buku->id]['qty'] : 0;
        $newQty = $currentQty + $qtyAdd;

        if ($newQty > $dbStok) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi.',
                'remaining' => $dbStok - $currentQty
            ], 422);
        }

        // set / update item di session
        $keranjang[$buku->id] = [
            'id' => $buku->id,
            'judul_buku' => $buku->judul_buku,
            'harga' => (float) ($buku->Tbdetail->harga ?? 0),
            'qty' => $newQty,
            'subtotal' => (float) (($buku->Tbdetail->harga ?? 0) * $newQty),
        ];

        Session::put('keranjang', $keranjang);

        $remaining = $dbStok - $newQty;

        return response()->json([
            'status' => 'success',
            'keranjang' => $keranjang,
            'remaining' => $remaining,
            'count' => count($keranjang)
        ]);
    }

    // Update qty item di keranjang (route: kasir.keranjang.update)
    public function updateKeranjang(Request $r)
    {
        $r->validate([
            'id' => 'required|integer|exists:data_buku,id',
            'qty' => 'required|integer|min:1'
        ]);

        $id = (int)$r->id;
        $newQty = (int)$r->qty;

        $buku = DataBuku::with('Tbdetail')->findOrFail($id);
        $dbStok = (int) ($buku->Tbdetail->stok ?? 0);

        if ($newQty > $dbStok) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi.',
                'remaining' => $dbStok
            ], 422);
        }

        $keranjang = Session::get('keranjang', []);

        if (isset($keranjang[$id])) {
            $keranjang[$id]['qty'] = $newQty;
            $keranjang[$id]['subtotal'] = $keranjang[$id]['harga'] * $newQty;
            Session::put('keranjang', $keranjang);
        } else {
            // kalau ga ada, buat baru (optional)
            $keranjang[$id] = [
                'id' => $buku->id,
                'judul_buku' => $buku->judul_buku,
                'harga' => (float) ($buku->Tbdetail->harga ?? 0),
                'qty' => $newQty,
                'subtotal' => (float) (($buku->Tbdetail->harga ?? 0) * $newQty),
            ];
            Session::put('keranjang', $keranjang);
        }

        $remaining = $dbStok - $newQty;

        return response()->json([
            'status' => 'success',
            'keranjang' => $keranjang,
            'remaining' => $remaining,
            'count' => count($keranjang)
        ]);
    }

    // Hapus item dari keranjang (route: kasir.keranjang.hapus)
    public function hapusKeranjang(Request $r)
    {
        $r->validate(['id' => 'required|integer|exists:data_buku,id']);

        $id = (int)$r->id;
        $keranjang = Session::get('keranjang', []);

        // ambil stok DB buat restore angka di card
        $buku = DataBuku::with('Tbdetail')->find($id);
        $dbStok = (int) ($buku->Tbdetail->stok ?? 0);

        if (isset($keranjang[$id])) {
            unset($keranjang[$id]);
            Session::put('keranjang', $keranjang);
        }

        return response()->json([
            'status' => 'success',
            'keranjang' => $keranjang,
            'remaining' => $dbStok,
            'count' => count($keranjang)
        ]);
    }

    // Ambil isi keranjang (route: kasir.keranjang.get)
    public function getkeranjang()
    {
        $keranjang = Session::get('keranjang', []);
        return response()->json([
            'status' => 'success',
            'keranjang' => $keranjang,
            'count' => count($keranjang)
        ]);
    }

    // Checkout (route: kasir.checkout)
    public function checkout(Request $r)
    {
        $r->validate([
            'metode_bayar' => 'required|string|in:cash,cashless',
            'diskon' => 'nullable|numeric|min:0',
            'dibayar' => 'nullable|numeric|min:0'
        ]);

        $keranjang = Session::get('keranjang', []);
        if (empty($keranjang)) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong'], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($keranjang as $it) {
                $subtotal += $it['subtotal'];
            }

            $diskon = (float) ($r->diskon ?? 0);
            $total_harga = $subtotal - $diskon;
            $dibayar = (float) ($r->dibayar ?? $total_harga);

            if ($r->metode_bayar === 'cash' && $dibayar < $total_harga) {
                return response()->json(['status' => 'error', 'message' => 'Uang dibayar kurang'], 422);
            }

            $transaksi = Transaksi::create([
                'kasir_id' => Auth::id(),
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total_harga' => $total_harga,
                'dibayar' => $dibayar,
                'kembalian' => $dibayar - $total_harga,
                'metode_bayar' => $r->metode_bayar,
            ]);

            foreach ($keranjang as $it) {
                $detail = DetailBuku::where('buku_id', $it['id'])->lockForUpdate()->first();
                if (!$detail || $detail->stok < $it['qty']) {
                    throw new \Exception("Stok tidak cukup untuk {$it['judul_buku']}");
                }

                $detail->stok -= $it['qty'];
                $detail->save();

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'buku_id' => $it['id'],
                    'qty' => $it['qty'],
                    'harga_satuan' => $it['harga'],
                    'subtotal' => $it['subtotal'],
                ]);
            }

            DB::commit();
            Session::forget('keranjang');

            return response()->json([
                'status' => 'success',
                'redirect' => route('kasir.struk', $transaksi->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // tambahin biar keliatan error detailnya
            ], 500);
        }
    }

    // Struk (route: kasir.struk)
    public function struk($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        return view('kasir.struk', compact('transaksi'));
    }


    public function riwayatTransaksi()
    {
        $riwayat = Transaksi::with('details.buku')->latest()->paginate(10);

        return view('kasir.riwayat.index', compact('riwayat'));
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
