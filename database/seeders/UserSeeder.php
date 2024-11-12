<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = 'password';
        $lists = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt($password),
                'group_id' => 1,
            ],
            [
                'name' => 'Manajer Satu',
                'email' => 'manajer1@mail.com',
                'password' => bcrypt($password),
                'group_id' => 2,
            ],
            [
                'name' => 'Manajer Dua',
                'email' => 'manajer2@mail.com',
                'password' => bcrypt($password),
                'group_id' => 2,
            ],
        ];
        User::insert($lists);
    }
}
