<?php

namespace Database\Seeders;

use App\Models\Employee as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $length = 2;
        for ($i = 1; $i <= $length; $i++) {
            $object = [
                'name'              => 'Employee ' . $i,
                'code'              => generateCodeEmployee(),
                'identity_number'   => '112233443' . $i,
                'email'             => 'emailemployee' . $i . '@mail.com',
                'phone_number'      => '08523355162' . $i,
                'gender'            => 'Laki-laki',
                'address'           => 'Alamat ' . $i,
                'description'       => 'Deskripsi ' . $i,
            ];
            Model::create($object);
            sleep(1);
        }
    }
}
