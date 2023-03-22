<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends Controller
{
    public function __construct(private ServiceRepositoryInterface $serviceRepository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return $this->serviceRepository->getServices();
    }
}
