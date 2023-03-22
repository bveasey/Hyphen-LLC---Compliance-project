<?php

namespace App\Repositories;

use App\Http\Resources\RoleResource;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function getRoles(): AnonymousResourceCollection
    {
        return RoleResource::collection(
            Role::all()
        );
    }
}
