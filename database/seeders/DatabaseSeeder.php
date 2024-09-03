<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pedro',
            'email' => 'pedro110302@gmail.com',
            'password' => Hash::make('pedro123'),
        ]);

        Category::factory(5)->create();
        
        Transaction::factory(50)->create();

        Transaction::factory(50)->create([
            'type' => 'expense',
        ]);

    }
}
