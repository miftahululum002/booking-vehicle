<?php

namespace Database\Seeders;

use App\Models\Vehicle as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $length = 3;
        for ($i = 1; $i <= $length; $i++) {
            $object = [
                'name' => 'Mobil ' . $i,
                'code' => generateCodeVehicle(),
                'merk' => 'Merk ' . $i,
                'category_id' => 1,
                'description' => 'Deskripsi ' . $i,
            ];
            Model::create($object);
            sleep(1);
        }
    }
}
