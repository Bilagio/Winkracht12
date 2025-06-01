<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test customer user that will definitely work
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'customer User',
                'password' => Hash::make('1'),
                'role' => 'customer',
                'notification_preferences' => User::getDefaultNotificationPreferences(),
            ]
        );

        // Create a test admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'notification_preferences' => User::getDefaultNotificationPreferences(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'OGAdmin69@gmail.com'],
            [
                'name' => 'OG Admin 69 supa c00l',
                'password' => Hash::make("bussin ho's"),
                'role' => 'admin',
                'notification_preferences' => User::getDefaultNotificationPreferences(),
            ]
        );

        // Create a test instructor user
        User::updateOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'Instructor User',
                'password' => Hash::make('instructor123'),
                'role' => 'instructor',
                'notification_preferences' => User::getDefaultNotificationPreferences(),
            ]
        );

        // This seeder should create an admin user with predefined credentials
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@windkracht13.nl', // Or similar predefined email
                'password' => Hash::make('secure_password'), // Hashed password
                'role' => 'admin',
                'email_verified_at' => now(), // Pre-verified
                'notification_preferences' => User::getDefaultNotificationPreferences(),
            ]
        ];
    }
}
