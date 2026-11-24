<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Reguler',
                'harga' => 7000,
                'estimasi' => '3-4 hari',
            ],
            [
                'nama_layanan' => 'Ekspres',
                'harga' => 12000,
                'estimasi' => '1-2 hari',
            ],
            [
                'nama_layanan' => 'Superkilat',
                'harga' => 25000,
                'estimasi' => '5 jam',
            ],
        ];

        foreach ($layanans as $layanan) {
            Layanan::updateOrCreate(
                ['nama_layanan' => $layanan['nama_layanan']],
                $layanan
            );
        }
    }
}
