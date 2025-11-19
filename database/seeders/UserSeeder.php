<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('kel321'),
                'role' => 'admin',
            ],
            [
                'username' => 'kasir',
                'password' => Hash::make('kel321'),
                'role' => 'cashier',
            ],
        ];
        foreach($users as $user){
            User::createOrFirst($user);
        }
    }
}
