<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            // Create admin user
            User::create([
                'first_name' => 'Админ',
                'middle_name' => 'Админович',
                'last_name' => 'Админов',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN->value,
            ]);
        }

        // Check if Ivan user exists
        if (!User::where('email', 'ivan@example.com')->exists()) {
            // Create regular user
            User::create([
                'first_name' => 'Иван',
                'middle_name' => 'Иванович',
                'last_name' => 'Иванов',
                'email' => 'ivan@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::USER->value,
            ]);
        }

        // Check if Maria user exists
        if (!User::where('email', 'maria@example.com')->exists()) {
            User::create([
                'first_name' => 'Мария',
                'middle_name' => 'Петровна',
                'last_name' => 'Сидорова',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'role' => UserRole::USER->value,
            ]);
        }

        // Create additional test users only if we have fewer than 10 regular users
        $userCount = User::where('role', UserRole::USER->value)->count();
        if ($userCount < 10) {
            $count = 10 - $userCount;
            User::factory($count)->create([
                'role' => UserRole::USER->value
            ]);
        }
    }
}
