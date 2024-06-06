<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Transaction::factory(3)->create([
            'date' => '2024-01-15',
        ]);
        Transaction::factory(7)->create([
            'date' => '2024-01-15',
            'type' => 'expense',
        ]);
        Transaction::factory(5)->create([
            'date' => '2024-02-15',
        ]);
        Transaction::factory(5)->create([
            'date' => '2024-02-15',
            'type' => 'expense',
        ]);
        Transaction::factory(4)->create([
            'date' => '2024-03-15',
        ]);
        Transaction::factory(6)->create([
            'date' => '2024-03-15',
            'type' => 'expense',
        ]);
        Transaction::factory(2)->create([
            'date' => '2024-04-15',
        ]);
        Transaction::factory(8)->create([
            'date' => '2024-04-15',
            'type' => 'expense',
        ]);
        Transaction::factory(4)->create([
            'date' => '2024-05-15',
        ]);
        Transaction::factory(6)->create([
            'date' => '2024-05-15',
            'type' => 'expense',
        ]);
        Transaction::factory(5)->create([
            'date' => '2024-06-15',
        ]);
        Transaction::factory(5)->create([
            'date' => '2024-06-15',
            'type' => 'expense',
        ]);
    }
}
