<?php

namespace Database\Seeders;

use Database\Seeders\Staging\BrandSeeder;
use Database\Seeders\Staging\RolesSeeder;
use Database\Seeders\Staging\ServiceSeeder;
use Database\Seeders\Staging\UserSeeder;
use Illuminate\Database\Seeder;

class StagingSeeder extends Seeder
{
    /**
     * Seed the staging application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RolesSeeder::class,
            ServiceSeeder::class,
            BrandSeeder::class,
        ]);
    }
}
