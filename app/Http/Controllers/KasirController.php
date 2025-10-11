<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return view('kasir.riwayat-transaksi.index', compact('riwayat'));
    }

    public function riwayatKasir(Request $request)
    {
        $user = auth()->user();

        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->toDateString();

        $kasirData = Transaksi::select(
            DB::raw('DATE(transaksi.created_at) as tanggal'),
            DB::raw('COUNT(transaksi.id) as total_transaksi'),
            DB::raw('SUM(transaksi_detail.qty) as total_item'),
            DB::raw('SUM(transaksi.total_harga) as total_belanja')
        )
            ->leftJoin('transaksi_detail', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
            ->where('kasir_id', auth()->id())
            ->whereDate('transaksi.created_at', '>=', $start)
            ->whereDate('transaksi.created_at', '<=', $end)
            ->groupBy(DB::raw('DATE(transaksi.created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('kasir.riwayat.index', compact('kasirData', 'start', 'end'));
    }
}
