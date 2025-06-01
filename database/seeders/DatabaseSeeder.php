<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            LocationSeeder::class,
            LessonPackageSeeder::class,
            DemoDataSeeder::class,
            // Add any other seeders here
        ]);
    }
}
