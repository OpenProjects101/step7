<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
              //'name' => 'admin',
              'email' => 'admin@example.com',
              'password' => Hash::make('1111')
            ],
            [
              //'name' => 'member',
              'email' => 'member@example.com',
              'password' => Hash::make('2222')
            ],
            [
              //'name' => 'creator',
              'email' => 'creator@example.com',
              'password' => Hash::make('3333')
            ]
          ]);
    }
}
