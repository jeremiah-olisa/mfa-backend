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

    public function getUserProfileByUserId(int $userId): array
    {
        $user = $this->findOneByOrThrow('id', $userId, 'profile');

        $fullName = $user?->name;

        // Split name into first and last parts or set default values
        [$firstName, $lastName] = $fullName
            ? explode(' ', $fullName, 2) + [null, null]
            : [null, null];

        return [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $user?->email,
            'phone' => $user?->profile?->phone ?? null,
            'plan' => $user?->profile?->plan ?? null,
            'plan_duration' => $user?->profile?->plan_duration ?? null,
            'plan_expires_at' => $user?->profile?->plan_expires_at ?? null,
            'student_status' => $user?->student_status ?? '1', // Default to '1' if not set
            'last_login' => $user?->last_login ?? now()->toDateTimeString(),
            'deviceID' => $user?->device_id ?? null,
            'parent_email' => $user?->profile?->parent_email ?? null,
            'parent_phone' => $user?->profile?->parent_phone ?? null,
        ];
    }
}
