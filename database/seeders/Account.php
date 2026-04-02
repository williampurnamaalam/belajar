<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Account extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('users')->insert([
            [
                'nama'          => 'admin',
                'email'         => 'admin@gmail.com',
                'password'      => Hash::make('admin'), 
                'role_id'       => '1', 
                'tanggal_lahir' => '1990-01-01',
                'telepon'       => '08123456789',
                'is_active'     => '1',
                'nik'           => '1234567890123456',
                'gender'        => 'Pria',
                'created_at'    => now(),
            ]

        
        ]);
    }
}
