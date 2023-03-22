<?php

namespace App\Repositories;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandRepository implements BrandRepositoryInterface
{
    public function getBrands(): AnonymousResourceCollection
    {
        return BrandResource::collection(
            Brand::all()
        );
    }

    public function updateBrandStatus(Brand $brand, array $payload): BrandResource
    {
        $brand->brandStatuses()->create([
            'status' => $payload['status'],
        ]);

        return new BrandResource($brand);
    }
}
