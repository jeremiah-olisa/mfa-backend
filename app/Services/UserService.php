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
     * @return array
     */
    public function updateUserProfile(array $data, int|string|null $user_id = null)
    {
        DB::beginTransaction();

        try {
            // Fetch user by ID
            $userId = $user_id ?? Auth::id();
            $user = $this->userRepository->findOneByOrThrow('id', $userId, [], ['id']);
            // Prepare the data for User model (email, name)
            $updateData = $this->extractUpdateUserData($data);

            // Update User model if data exists
            if (!empty($updateData)) {
                $user = $this->userRepository->update($userId, $updateData);
            }

            // Prepare the data for UserProfile model
            $profileData = [
                'user_id' => $userId,
                'phone' => $data['phone'] ?? null,
                'parent_email' => $data['parent_email'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
            ];

            // Update UserProfile model
            $this->userProfileRepository->upsert(
                [$profileData], // Data
                ['user_id'],    // Unique constraint columns
                ['phone', 'parent_email', 'parent_phone'] // Columns to update
            );


            // Commit the transaction if everything is successful
            DB::commit();

            return array_merge($user->toArray(), ['profile' => $profileData]);

        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Optionally, log the exception or rethrow it
            throw new \Exception("Error updating profile: " . $e->getMessage());
        }
    }

    public function getCurrentUserProfile()
    {
        $userId = Auth::id();

        return $this->userRepository->getUserProfileByUserId($userId);

    }


    /**
     * @param array $data
     * @return array
     */
    protected function extractUpdateUserData(array $data): array
    {
        $updateData = [];
        if (isset($data['email'])) {
            $updateData['email'] = $data['email'];
        }

        if (isset($data['first_name']) || isset($data['last_name'])) {
            $updateData['name'] = trim($data['first_name'] . ' ' . $data['last_name']);
        }
        return $updateData;
    }
}
