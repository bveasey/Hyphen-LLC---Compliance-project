<?php

namespace Database\Seeders;

use Database\Seeders\Local\BrandSeeder;
use Database\Seeders\Local\RolesSeeder;
use Database\Seeders\Local\ServiceSeeder;
use Database\Seeders\Local\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the local application's database.
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
