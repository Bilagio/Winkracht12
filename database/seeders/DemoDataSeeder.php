<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reservation;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application with demo data.
     */
    public function run(): void
    {
        // Create 5 instructors
        \App\Models\User::factory()->count(5)->create([
            'role' => 'instructor',
        ]);
        
        // Create 20 customers
        \App\Models\User::factory()->count(20)->create([
            'role' => 'customer',
        ]);

        // Ensure LessonPackages and Locations exist before creating reservations
        if (\App\Models\LessonPackage::count() === 0) {
            $this->command->info('Please run the LessonPackageSeeder first');
            return;
        }
        
        if (\App\Models\Location::count() === 0) {
            $this->command->info('Please run the LocationSeeder first');
            return;
        }

        // Create 30 reservations with different statuses
        \App\Models\Reservation::factory()->confirmed()->count(15)->create();
        \App\Models\Reservation::factory()->pending()->count(5)->create();
        \App\Models\Reservation::factory()->completed()->count(20)->create();
        \App\Models\Reservation::factory()->cancelled()->count(10)->create();
        
        $this->command->info('Demo data seeded successfully!');
    }
}
