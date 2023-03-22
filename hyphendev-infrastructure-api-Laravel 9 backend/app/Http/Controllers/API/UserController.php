<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Authenticates a User.
     *
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $userData = $this->userRepository->loginUser($request->validated());

        return response()->json([
            'message' => __('messages.login-successful'),
            'data' => $userData,
        ], Response::HTTP_OK);
    }

    /**
     * Un-Authenticates a User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->userRepository->logoutUser($request->user());

        return response()->json([
            'message' => __('messages.logout-successful'),
        ], Response::HTTP_OK);
    }

    /**
     * Gets a UserResource.
     * @return UserResource
     */
    public function getAuthedUser(Request $request): UserResource
    {
        return $this->userRepository->getUser($request->user());
    }

    public function index(): AnonymousResourceCollection
    {
        return $this->userRepository->getUsers();
    }

    public function show(User $user): UserResource
    {
        return $this->userRepository->getUser($user);
    }

    public function store(CreateUserRequest $request): UserResource
    {
        return $this->userRepository->createUser($request->validated());
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        return $this->userRepository->updateUser($request->validated(), $user);
    }

    public function destroy(User $user): Response
    {
        return $this->userRepository->destroyUser($user);
    }
}
