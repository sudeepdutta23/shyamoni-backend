<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

        $this->call([
            UsersTableSeeder::class,
            RoleMasterSeeder::class,
            stateMasterSeeder::class,
            countryMasterSeeder::class,
            CitySeeder::class,
            stateMasterSeeder::class,
            mailMessages::class,
            // weightSeeder::class,
            jobSeeder::class,
            activityLogSeeder::class
        ]);

    }
}
