<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBuku extends Model
{
    protected $table = 'detail_buku';
    protected $fillable = [
        'id_buku',
        'stok',
        'harga'
    ];

    public function buku()
    {
        return $this->belongsTo(DataBuku::class, 'id_buku');
    }
}
