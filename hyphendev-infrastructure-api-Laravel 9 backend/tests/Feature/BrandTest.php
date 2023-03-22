<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\BrandStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function testCanGetBrandsWithCurrentStatus()
    {
        $activeBrand = Brand::factory()
            ->has(BrandStatus::factory()->active())
            ->create();

        $inactiveBrand = Brand::factory()
            ->has(BrandStatus::factory()->inactive())
            ->create();

        $response = $this->getJson(route('brands.index'));

        $data = collect(json_decode($response->getContent())->data);

        $this->assertCount(2, $data);

        $this->assertEquals($activeBrand->getKey(), $data->first()->id);
        $this->assertEquals(BrandStatus::ACTIVE, $data->first()->currentStatus);

        $this->assertEquals($inactiveBrand->getKey(), $data->last()->id);
        $this->assertEquals(BrandStatus::INACTIVE, $data->last()->currentStatus);
    }

    /**
     * @test
     * @return void
     */
    public function testCanUpdateBrandStatus()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name . '-token')->plainTextToken;

        $brand = Brand::factory()
            ->has(
                BrandStatus::factory()
                    ->active()
                    ->state(function (array $attributes) {
                        return [
                            'created_at' => now()->subDay(),
                            'updated_at' => now()->subDay(),
                        ];
                    })
            )
            ->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->put(route('brands.update', $brand), [
                'status' => BrandStatus::INACTIVE,
            ]);

        $data = json_decode($response->getContent())->data;

        $this->assertEquals($brand->getKey(), $data->id);
        $this->assertEquals(BrandStatus::INACTIVE, $data->currentStatus);

        $this->assertDatabaseHas('brand_statuses', [
            'brand_id' => $brand->getKey(),
            'status' => BrandStatus::ACTIVE,
        ]);

        $this->assertDatabaseHas('brand_statuses', [
            'brand_id' => $brand->getKey(),
            'status' => BrandStatus::INACTIVE,
        ]);
    }
}
