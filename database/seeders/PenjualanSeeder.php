<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Transaksi 1
            [
                'penjualan_id' => 1,
                'user_id' => 1,
                'pembeli' => 'Adinda Siska',
                'penjualan_kode' => 'PNJ001',
                'penjualan_tanggal' => '2024-09-01',
            ],
            // Transaksi 2
            [
                'penjualan_id' => 2,
                'user_id' => 1,
                'pembeli' => 'Alifia',
                'penjualan_kode' => 'PNJ002',
                'penjualan_tanggal' => '2024-09-02',
            ],
            // Transaksi 3
            [
                'penjualan_id' => 3,
                'user_id' => 1,
                'pembeli' => 'Michael Johnson',
                'penjualan_kode' => 'PNJ003',
                'penjualan_tanggal' => '2024-09-03',
            ],
            // Transaksi 4
            [
                'penjualan_id' => 4,
                'user_id' => 1,
                'pembeli' => 'Fajar Ega',
                'penjualan_kode' => 'PNJ004',
                'penjualan_tanggal' => '2024-09-04',
            ],
            // Transaksi 5
            [
                'penjualan_id' => 5,
                'user_id' => 1,
                'pembeli' => 'Purnomo',
                'penjualan_kode' => 'PNJ005',
                'penjualan_tanggal' => '2024-09-05',
            ],
            // Transaksi 6
            [
                'penjualan_id' => 6,
                'user_id' => 1,
                'pembeli' => 'Olivia Brown',
                'penjualan_kode' => 'PNJ006',
                'penjualan_tanggal' => '2024-09-06',
            ],
            // Transaksi 7
            [
                'penjualan_id' => 7,
                'user_id' => 1,
                'pembeli' => 'James Garcia',
                'penjualan_kode' => 'PNJ007',
                'penjualan_tanggal' => '2024-09-07',
            ],
            // Transaksi 8
            [
                'penjualan_id' => 8,
                'user_id' => 1,
                'pembeli' => 'Sophia Martinez',
                'penjualan_kode' => 'PNJ008',
                'penjualan_tanggal' => '2024-09-08',
            ],
            // Transaksi 9
            [
                'penjualan_id' => 9,
                'user_id' => 1,
                'pembeli' => 'Liam Anderson',
                'penjualan_kode' => 'PNJ009',
                'penjualan_tanggal' => '2024-09-09',
            ],
            // Transaksi 10
            [
                'penjualan_id' => 10,
                'user_id' => 1,
                'pembeli' => 'Isabella Thomas',
                'penjualan_kode' => 'PNJ010',
                'penjualan_tanggal' => '2024-09-10',
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
