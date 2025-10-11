<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;
    protected $kasirId;

    public function __construct($start, $end, $kasirId)
    {
        $this->start = $start;
        $this->end = $end;
        $this->kasirId = $kasirId;
    }

    public function collection()
    {
        $query = Transaksi::with('kasir', 'details.buku')
            ->whereDate('created_at', '>=', $this->start)
            ->whereDate('created_at', '<=', $this->end);

        if ($this->kasirId) {
            $query->where('kasir_id', $this->kasirId);
        }

        $transaksi = $query->get();

        return $transaksi->map(function ($t) {
            return [
                'Tanggal' => $t->created_at->format('d-m-Y H:i'),
                'Kasir' => $t->kasir->name ?? '-',
                'Total Transaksi' => $t->details->count(),
                'Total Item' => $t->details->sum('qty'),
                'Total Penjualan' => $t->details->sum(fn($d) => $d->qty * $d->harga_satuan),
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kasir', 'Total Transaksi', 'Total Item', 'Total Penjualan'];
    }
}
