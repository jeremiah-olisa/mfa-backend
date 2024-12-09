<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * @template T of UserProfile
 * @template-inherits BaseRepository<T>
 */
class UserProfileRepository extends BaseRepository
{
    public function __construct(UserProfile $userProfile)
    {
        parent::__construct($userProfile);
    }

    public function updateExpiry(int $userId, int $duration, string $plan)
    {
        // Retrieve the user profile by user ID
        $user = $this->findOneByOrThrow('user_id', $userId);


        $currentExpiry = $user->plan_expires_at;

        if ($currentExpiry && Carbon::parse($currentExpiry)->isFuture()) {
            $user->plan_expires_at = Carbon::parse($currentExpiry)->addDays($duration);
        } else {
            $user->plan_expires_at = now()->addDays($duration);
        }

        $user->plan = $plan;
        $user->save();

        return $user->plan_expires_at;
    }

}
