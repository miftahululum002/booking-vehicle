<?php

namespace Database\Seeders;

use App\Models\Category as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = [
            [
                'name' => 'Diesel',
                'description' => 'Kategori kendaraan mesin diesel',
                'created_by' => 1,
            ],
            [
                'name' => 'Bensin',
                'description' => 'Kategori kendaraan mesin bensin',
                'created_by' => 1,
            ],
            [
                'name' => 'Listrik',
                'description' => 'Kategori kendaraan mesin listrik',
                'created_by' => 1,
            ],
        ];

        Model::insert($lists);
    }
}
