<?php

namespace App\Repositories\Contracts;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

interface UserRepositoryInterface
{
    public function loginUser(array $payload): array;

    public function logoutUser(User $user): bool;

    public function getUser(User $user): UserResource;

    public function getUsers(): AnonymousResourceCollection;

    public function destroyUser(User $user): Response;

    public function createUser(array $payload): UserResource;

    public function updateUser(array $payload, User $user): UserResource;
}
