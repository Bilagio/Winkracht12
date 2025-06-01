<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, remove all existing packages
        DB::table('lesson_packages')->delete();

        // Insert the new packages
        DB::table('lesson_packages')->insert([
            [
                'name' => 'Privéles',
                'description' => '2,5 uur privéles, inclusief alle materialen. Eén persoon per les, 1 dagdeel.',
                'duration' => 150, // 2.5 hours in minutes
                'price' => 175.00,
                'max_participants' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Losse Duo Kiteles',
                'description' => '3,5 uur les, € 135,- per persoon inclusief alle materialen. Maximaal 2 personen per les, 1 dagdeel.',
                'duration' => 210, // 3.5 hours in minutes
                'price' => 135.00,
                'max_participants' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kitesurf Duo lespakket 3 lessen',
                'description' => '10,5 uur totaal, € 375,- per persoon inclusief materialen. Maximaal 2 personen per les, 3 dagdelen.',
                'duration' => 630, // 10.5 hours in minutes
                'price' => 375.00,
                'max_participants' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kitesurf Duo lespakket 5 lessen',
                'description' => '17,5 uur totaal, € 675,- per persoon inclusief materialen. Maximaal 2 personen per les, 5 dagdelen.',
                'duration' => 1050, // 17.5 hours in minutes
                'price' => 675.00,
                'max_participants' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // The down method could restore the original packages,
        // but for simplicity, we'll just truncate since the original data is in seeders
        DB::table('lesson_packages')->delete();
    }
};
