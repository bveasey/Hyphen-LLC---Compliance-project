<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function loginUser(array $payload): array
    {
        $user = User::where('email', $payload['email'])->first();

        if (!$user || !Hash::check($payload['password'], $user->password)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        $token = $user->createToken("{$user->name}-token");

        return [
            'user' => new UserResource($user),
            'token' => $token->plainTextToken,
            'token_type' => 'bearer',
        ];
    }

    public function logoutUser(User $user): bool
    {
        return $user->currentAccessToken()->delete();
    }

    public function getUsers(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::all()
        );
    }

    public function destroyUser(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }

    public function getUser(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function createUser(array $payload): UserResource
    {
        $newUser = User::create($payload);
        $newUser->roles()->sync($payload['roles']);

        return new UserResource($newUser);
    }

    public function updateUser(array $payload, User $user): UserResource
    {
        if (!$payload['password']) {
            unset($payload['password']);
        }

        $user->update($payload);

        $user->roles()->sync($payload['roles']);

        return new UserResource($user);
    }
}
