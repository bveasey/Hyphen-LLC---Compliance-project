<?php

namespace App\Repositories;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function getServices(): AnonymousResourceCollection
    {
        return ServiceResource::collection(
            Service::all()
        );
    }
}
