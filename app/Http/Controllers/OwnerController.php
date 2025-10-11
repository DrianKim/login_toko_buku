<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DataBuku;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\LaporanExport;
use App\Models\TransaksiDetail;

class OwnerController extends Controller
{

    public function index()
    {
        $totalTransaksi = Transaksi::count();
        $totalPenjualan = Transaksi::with('details')->get()->sum(function ($t) {
            return $t->details->sum(fn($d) => $d->qty * $d->harga_satuan);
        });
        $barangTerjual = TransaksiDetail::sum('qty');
        $totalKasir = User::where('role', 'kasir')->count();
        $recentTransaksi = Transaksi::with(['kasir', 'details'])->orderBy('created_at', 'desc')->take(10)->get();

        // Chart Bulanan (placeholder)
        $salesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $salesData = [];
        foreach ($salesLabels as $i) {
            $salesData[] = Transaksi::whereMonth('created_at', $i)->sum('total_harga');
        }

        return view('owner.dashboard', compact(
            'totalTransaksi',
            'totalPenjualan',
            'barangTerjual',
            'totalKasir',
            'recentTransaksi',
            'salesLabels',
            'salesData'
        ));
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
            'users' => User::orderByRaw("FIELD(role, 'owner', 'owner', 'kasir')")->paginate(10),
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
            'role' => 'required|in:owner,kasir',
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
            'role' => 'required|in:owner,kasir',
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

    public function laporan(Request $request)
    {
        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->toDateString();
        $kasirId = $request->kasir_id ?? null;

        if ($request->has('export')) {
            $filename = 'laporan_' . Carbon::now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new LaporanExport($start, $end, $kasirId), $filename);
        }

        $transaksiQuery = Transaksi::with(['kasir', 'details.buku'])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        if ($kasirId) $transaksiQuery->where('kasir_id', $kasirId);

        $transaksi = $transaksiQuery->orderBy('created_at', 'desc')->get();

        $totalTransaksi = $transaksi->count();
        $totalPenjualan = $transaksi->sum(fn($t) => $t->details->sum(fn($d) => $d->qty * $d->harga_satuan));
        $barangTerjual = $transaksi->sum(fn($t) => $t->details->sum('qty'));

        $kasirList = User::where('role', 'kasir')->get();

        return view('owner.laporan.index', compact(
            'transaksi',
            'totalTransaksi',
            'totalPenjualan',
            'barangTerjual',
            'kasirList',
            'start',
            'end',
            'kasirId'
        ));
    }

    public function struk($id)
    {
        $transaksi = Transaksi::with(['kasir', 'details.buku'])->findOrFail($id);
        return view('owner.struk', compact('transaksi'));
    }
}
