<?php

namespace Database\Seeders\Local;

use App\Models\Brand;
use App\Models\BrandStatus;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            'Helix',
            'Vault',
            'Atlas',
            'Loop',
            'Raven',
        ];

        foreach ($brands as $brand) {
            $newBrand = Brand::create([
                'name' => $brand,
            ]);

            $newBrand->brandStatuses()->create(['status' => BrandStatus::ACTIVE]);
        }
    }
}
