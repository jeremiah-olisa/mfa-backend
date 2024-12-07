<?php

namespace App\Services;

use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected UserRepository $userRepository;
    protected UserProfileRepository $userProfileRepository;

    public function __construct(UserRepository $userRepository, UserProfileRepository $userProfileRepository)
    {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    /**
     * Update the user's email, name, and profile data.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function updateUserProfile(array $data)
    {
        DB::beginTransaction();

        try {
            // Fetch user by ID
            $user = $this->userRepository->findOneByOrThrow('id', Auth::id());

            // Prepare the data for User model (email, name)
            $updateData = [];
            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }

            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }

            // Update User model if data exists
            if (!empty($updateData)) {
                $this->userRepository->update($user, $updateData);
            }

            // Prepare the data for UserProfile model
            $profileData = [
                'phone' => $data['phone'] ?? null,
                'parent_email' => $data['parent_email'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
            ];

            // Update UserProfile model
            $this->userProfileRepository->updateBy('user_id', $user->id, $profileData);

            // Commit the transaction if everything is successful
            DB::commit();

            return $user;

        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Optionally, log the exception or rethrow it
            throw new \Exception("Error updating profile: " . $e->getMessage());
        }
    }
}
