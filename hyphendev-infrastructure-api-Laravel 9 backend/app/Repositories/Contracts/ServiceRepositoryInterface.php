<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface ServiceRepositoryInterface
{
    public function getServices(): AnonymousResourceCollection;
}
