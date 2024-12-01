<?php

namespace App\Repositories;

use App\Models\UserApp;
use Illuminate\Support\Facades\Hash;

/**
 * @template T of UserApp
 * @template-inherits BaseRepository<T>
 */
class UserAppRepository extends BaseRepository
{
    public function __construct(UserApp $userApp)
    {
        parent::__construct($userApp);
    }
}
