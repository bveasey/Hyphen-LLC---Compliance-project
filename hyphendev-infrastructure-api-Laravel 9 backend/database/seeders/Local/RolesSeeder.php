<?php

namespace Database\Seeders\Local;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the Role seeds for local environment.
     *
     * @return void
     */
    public function run(): void
    {
        $adminUserEmails = ['jmc@hyphenfi.com', 'matt@hyphenfi.com'];

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $adminUsers = User::whereIn('email', $adminUserEmails)->get();
        $basicUsers = User::whereNotIn('email', $adminUserEmails)->get();

        $adminUsers->each->assignRole('admin');
        $basicUsers->each->assignRole('user');
    }
}
