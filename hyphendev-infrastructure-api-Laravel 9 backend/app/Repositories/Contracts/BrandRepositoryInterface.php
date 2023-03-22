<?php

namespace App\Repositories\Contracts;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface BrandRepositoryInterface
{
    public function getBrands(): AnonymousResourceCollection;

    public function updateBrandStatus(Brand $brand, array $payload): BrandResource;
}
