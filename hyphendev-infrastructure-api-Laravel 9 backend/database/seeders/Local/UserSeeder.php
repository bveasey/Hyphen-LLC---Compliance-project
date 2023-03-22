<?php

namespace Database\Seeders\Local;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the User seeds for local environment.
     * @return void
     */
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'Jeff McReynolds',
            'email' => 'jmc@hyphenfi.com',
            'email_verified_at' => now(),
        ], [
            'password' => 'hyphen-Jeff-2022',
        ]);

        User::firstOrCreate([
            'name' => 'Matt Drouin',
            'email' => 'matt@hyphenfi.com',
            'email_verified_at' => now(),
        ], [
            'password' => 'hyphen-Matt-2022',
        ]);

        User::firstOrCreate([
            'name' => 'Kevin Downs',
            'email' => 'kevin@hyphenfi.com',
            'email_verified_at' => now(),
        ], [
            'password' => 'hyphen-Kevin-2022',
        ]);

        User::firstOrCreate([
            'name' => 'Brandon Veasey',
            'email' => 'brandonveasey@hyphenfi.com',
            'email_verified_at' => now(),
        ], [
            'password' => 'hyphen-Brandon-2022',
        ]);
    }
}
