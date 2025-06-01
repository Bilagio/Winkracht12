<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LessonPackage;

class LessonPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Privéles',
                'description' => '2,5 uur privéles, inclusief alle materialen. Eén persoon per les, 1 dagdeel.',
                'duration' => 150, // 2.5 hours in minutes
                'price' => 175.00,
                'max_participants' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Losse Duo Kiteles',
                'description' => '3,5 uur les, € 135,- per persoon inclusief alle materialen. Maximaal 2 personen per les, 1 dagdeel.',
                'duration' => 210, // 3.5 hours in minutes
                'price' => 135.00,
                'max_participants' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Kitesurf Duo lespakket 3 lessen',
                'description' => '10,5 uur totaal, € 375,- per persoon inclusief materialen. Maximaal 2 personen per les, 3 dagdelen.',
                'duration' => 630, // 10.5 hours in minutes
                'price' => 375.00,
                'max_participants' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Kitesurf Duo lespakket 5 lessen',
                'description' => '17,5 uur totaal, € 675,- per persoon inclusief materialen. Maximaal 2 personen per les, 5 dagdelen.',
                'duration' => 1050, // 17.5 hours in minutes
                'price' => 675.00,
                'max_participants' => 2,
                'is_active' => true
            ]
        ];

        foreach ($packages as $package) {
            LessonPackage::firstOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
