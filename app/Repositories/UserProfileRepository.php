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

}
