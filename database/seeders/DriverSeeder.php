<?php

namespace Database\Seeders;

use App\Models\Driver as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $length = 1;
        for ($i = 1; $i <= $length; $i++) {
            $object = [
                'name'              => 'Sopir ' . $i,
                'code'              => generateCodeDriver(),
                'description'       => 'Deskripsi ' . $i,
            ];
            Model::create($object);
            sleep(1);
        }
    }
}
