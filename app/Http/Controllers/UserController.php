<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function update(UpdateUserProfileRequest $request)
    {
        $data = $request->validated();

        $userProfile = $this->userService->updateUserProfile($data);

        return $this->api_response('User profile updated successfully.', ['data' => $userProfile]);
    }
}
