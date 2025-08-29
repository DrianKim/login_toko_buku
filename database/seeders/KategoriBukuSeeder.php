<?php

namespace Database\Seeders;

use App\Models\KategoriBuku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriBuku::create([
            'kategori' => 'Teknologi',
            'jenis' => 'Pemrograman',
        ]);
        KategoriBuku::create([
            'kategori' => 'Petualangan',
            'jenis' => 'Petualangan',
        ]);
    }
}
