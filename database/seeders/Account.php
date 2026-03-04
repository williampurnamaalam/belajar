<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Account extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user =
        [
            [
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'type'=> '1',
                'password' => bcrypt('admin')

            ]
        ];
        foreach ($user as $key=> $value)
        {
            User::create($value);
        }
    }
}
