<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    public function __construct(private BrandRepositoryInterface $brandRepository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return $this->brandRepository->getBrands();
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        return $this->brandRepository->updateBrandStatus($brand, $request->validated());
    }
}
