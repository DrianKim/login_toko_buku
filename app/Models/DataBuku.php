<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBuku extends Model
{
    protected $table = 'data_buku';
    protected $fillable = [
        'kode_buku',
        'judul_buku',
        'penerbit',
        'pengarang',
        'tahun_terbit',
        'cover_buku',
        'id_kategori',
    ];

    public function Tbkategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'id_kategori');
    }
}
