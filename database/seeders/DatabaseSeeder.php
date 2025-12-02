<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RegionSeeder::class,
            TownSeeder::class,
            EmployerSeeder::class,
            JobTitleSeeder::class,
            FaqSeeder::class,
            SiteSettingSeeder::class,
        ]);

        // Create admin user
        User::firstOrCreate(
            ['email' => 'transfers@eightyseventen.com'],
            [
                'username' => 'admin',
                'name' => 'System Administrator',
                'password' => 'password',
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'username' => 'testuser',
                'name' => 'Test User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
