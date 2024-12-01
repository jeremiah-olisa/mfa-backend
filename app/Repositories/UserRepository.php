<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @template T of User
 * @template-inherits BaseRepository<T>
 */
class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function create(array $data): User
    {
        $data["password"] = isset($data["password"]) && !empty($data["password"]) ? Hash::make($data["password"]) : null;

        return parent::create($data);
    }

    public function findByEmail($email, array $columns = ['*'], array $relations = []): User|null
    {
        return $this->findOneBy('email', $email, $columns, $relations);
    }

    public function userExistsByEmail($email)
    {
        return $this->exists('email', $email);
    }
}
