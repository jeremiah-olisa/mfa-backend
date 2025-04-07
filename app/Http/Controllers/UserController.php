<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
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
        $response = $this->userRepository->advancedCursorPaginate();

        $currentQueryParams = collect(request()->query())->except('page')->all();

        $pagination = [
            'next_page_url' => $response->nextPageUrl() ? 
                $response->nextPageUrl() . '&' . http_build_query($currentQueryParams) : 
                null,
            'prev_page_url' => $response->previousPageUrl() ? 
                $response->previousPageUrl() . '&' . http_build_query($currentQueryParams) : 
                null,
            'next_cursor' => $response->nextCursor(),
            'prev_cursor' => $response->previousCursor(),
            'per_page' => $response->perPage(),
            'current_page' => $response->path(),
            'has_more_pages' => $response->hasMorePages(),
            'path' => $response->path(),
            'with_query_string' => $response->withQueryString(),
        ];

        $data = [
            'users' => $response->items(),
            'pagination' => $pagination,
        ];

        if (!$request->wantsJson())
            return Inertia::render('Users/List', $data);

        return new JsonResponse($data);
    }
}
