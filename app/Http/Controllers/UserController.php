<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Utils\PaginationUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\GetUsersRequest;
use Inertia\Inertia;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserRepository $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userProfile = $this->userService->updateUserProfile($data);

        return $this->api_response('User profile updated successfully.', ['data' => $userProfile]);
    }


    public function getCurrentUserProfile(): JsonResponse
    {
        $user = $this->userService->getCurrentUserProfile();

        return $this->api_response('Profile retrieved successfully', ['data' => $user]);
    }

    public function list(GetUsersRequest $request)
    {
        $validatedQuery = $request->validated();
        $response = $this->userRepository->getUserAppList($validatedQuery, $request->query('per_page') ?? 15);

        $data = [
            'users' => $response['data'],
            'pagination' => $response['pagination'],
            'roles' => SetupConstant::$roles,
            'apps' => SetupConstant::$apps,
        ];

        if (!$request->wantsJson())
            return Inertia::render('Users/List', $data);

        return new JsonResponse($data);
    }
}
