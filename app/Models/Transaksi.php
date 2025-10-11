<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kasir_id',
        'total_harga',
        'diskon',
        'subtotal',
        'dibayar',
        'kembalian',
        'metode_bayar',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function buku()
    {
        return $this->belongsTo(DataBuku::class, 'buku_id');
    }

}
