<?php

namespace Database\Seeders;

use App\Models\DataBuku;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataBuku::create([
            'kode_buku' => 'B001',
            'judul_buku' => 'Pemrograman PHP untuk Pemula',
            'penerbit' => 'Tech Press',
            'pengarang' => 'John Doe',
            'tahun_terbit' => 2020,
            // 'cover_buku' => 'images/php_for_beginners.jpg',
            'id_kategori' => 1,
        ]);
    }
}
