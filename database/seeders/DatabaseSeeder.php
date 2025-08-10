<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Bobby Jones',
            'email' => 'bobbyjones@admin.com',
            'password' => Hash::make("2025LLTRB2%%")
        ]);

        User::factory()->create([
            'name' => 'Rafael',
            'email' => 'rafaprof@gmail.com',
            'password' => Hash::make("rafael2025Taek%%")
        ]);
    }
}
