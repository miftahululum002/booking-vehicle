<?php

namespace Database\Seeders;

use App\Models\UserGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = [
            [
                'name' => 'Admin',
                'description' => 'Group user admin'
            ],
            [
                'name' => 'Other',
                'description' => 'Group user other'
            ],
        ];

        UserGroup::insert($lists);
    }
}
