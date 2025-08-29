<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    protected $table = 'kategori_buku';
    protected $fillable = [
        'kategori',
        'jenis'
    ];

    public function buku()
    {
        return $this->hasMany(DataBuku::class, 'id_buku');
    }
}
