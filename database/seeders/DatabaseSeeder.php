<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(ProductsTableSeeder::class);
      $this->call(CompaniesTableSeeder::class);
      $this->call(SalesTableSeeder::class);
      // 他のシーダーも呼び出す場合は、ここに追加します。
    }
}
