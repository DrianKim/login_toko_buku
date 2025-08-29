<?php

namespace Database\Seeders;

use App\Models\DetailBuku;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DetailBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailBuku::create([
            'id_buku' => 1,
            'stok' => 50,
            'harga' => 150000.00,
        ]);
    }
}
