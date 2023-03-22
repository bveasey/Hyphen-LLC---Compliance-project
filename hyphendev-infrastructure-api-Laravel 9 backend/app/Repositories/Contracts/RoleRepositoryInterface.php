<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface RoleRepositoryInterface
{
    public function getRoles(): AnonymousResourceCollection;
}
