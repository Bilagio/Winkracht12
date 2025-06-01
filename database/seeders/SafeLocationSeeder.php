<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class SafeLocationSeeder extends Seeder
{
    /**
     * Run the database seeds safely by using firstOrCreate
     * to avoid duplicate entries.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Zandvoort Beach',
                'address' => 'Boulevard Paulus Loot 5',
                'city' => 'Zandvoort',
                'description' => 'A popular beach with consistent winds, perfect for beginners and intermediate kitesurfers.',
                'is_active' => true
            ],
            // ...other locations from your previous seeder...
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(
                ['name' => $location['name'], 'city' => $location['city']],
                $location
            );
        }
    }
}
