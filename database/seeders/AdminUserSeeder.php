<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin par défaut
        User::firstOrCreate(
            ['email' => 'admin@portfolio.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('AdminPassword123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer un utilisateur lecteur de démonstration
        User::firstOrCreate(
            ['email' => 'reader@portfolio.local'],
            [
                'name' => 'Reader User',
                'password' => Hash::make('ReaderPassword123!'),
                'role' => 'reader',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin and Reader users created successfully!');
        $this->command->info('Admin Email: admin@portfolio.local');
        $this->command->info('Admin Password: AdminPassword123!');
        $this->command->info('');
        $this->command->info('Reader Email: reader@portfolio.local');
        $this->command->info('Reader Password: ReaderPassword123!');
    }
}
