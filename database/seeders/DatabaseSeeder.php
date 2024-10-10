<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Slipstream User',
            'email' => 'demo@slipstream.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        Customer::factory(10)
            ->hasContacts(3)
            ->create();
    }
}
