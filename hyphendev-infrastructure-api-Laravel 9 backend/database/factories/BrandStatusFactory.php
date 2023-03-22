<?php

namespace Database\Factories;

use App\Models\BrandStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandStatusFactory extends Factory
{
    public function definition()
    {
        return [
            'brand_id' => fake()->randomDigit(),
            'status' => fake()->randomElement(BrandStatus::STATUSES),
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BrandStatus::ACTIVE,
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => BrandStatus::INACTIVE,
            ];
        });
    }
}
