<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function __construct(private RoleRepositoryInterface $roleRepository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return $this->roleRepository->getRoles();
    }
}
