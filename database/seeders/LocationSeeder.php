<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            [
                'name' => 'Scheveningen North Beach',
                'address' => 'Strandweg 3',
                'city' => 'The Hague',
                'description' => 'One of the Netherlands\' premier kitesurfing spots with excellent wave conditions.',
                'is_active' => true
            ],
            [
                'name' => 'Hoek van Holland',
                'address' => 'Zeekant 4',
                'city' => 'Rotterdam',
                'description' => 'Strong winds and a spacious beach make this an ideal location for experienced kitesurfers.',
                'is_active' => true
            ],
            [
                'name' => 'Ijmuiden Beach',
                'address' => 'Kennemerboulevard 250',
                'city' => 'IJmuiden',
                'description' => 'A wide beach with good wind conditions and fewer crowds than other spots.',
                'is_active' => true
            ],
            [
                'name' => 'Brouwersdam',
                'address' => 'Brouwersdam 6',
                'city' => 'Zeeland',
                'description' => 'Known as "Dutch Hawaii" for its consistent wind, perfect for freestyle and wave kitesurfing.',
                'is_active' => true
            ],
            [
                'name' => 'Texel - Paal 17',
                'address' => 'Paal 17 Beach Club',
                'city' => 'Texel',
                'description' => 'An island location with great wind statistics and beautiful natural surroundings.',
                'is_active' => true
            ],
            [
                'name' => 'Makkum Beach',
                'address' => 'De Holle Poarte 10',
                'city' => 'Makkum',
                'description' => 'A spacious beach on the IJsselmeer with shallow water, ideal for beginners.',
                'is_active' => true
            ],
            [
                'name' => 'Domburg Beach',
                'address' => 'Strand Domburg',
                'city' => 'Domburg',
                'description' => 'A charming beach town with excellent kitesurfing conditions in Zeeland.',
                'is_active' => true
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
