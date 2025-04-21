<?php

namespace App\Repositories;

use App\Models\Referral;
use App\Models\User;
use App\Utils\PaginationUtils;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        $data["device_id"] = Referral::generateDeviceId();

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

    public function getUserProfileByUserId(int $userId, string $app): array
    {
        $user = $this->findOneByOrThrow('id', $userId, 'profile');

        $fullName = $user?->name;

        // Split name into first and last parts or set default values
        [$firstName, $lastName] = $fullName
            ? explode(' ', $fullName, 2) + [null, null]
            : [null, null];

        $userApp = $user->userAppsByApp($app);

        return [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $user?->email,
            'phone' => $user?->profile?->phone ?? null,
            'plan' => $user?->profile?->plan ?? null,
            'plan_duration' => $userApp["plan_duration"] ?? null,
            'plan_expires_at' => $userApp["plan_expires_at"] ?? null,
            'student_status' => $user?->student_status ?? '1', // Default to '1' if not set
            'last_login' => $user?->last_login ?? now()->toDateTimeString(),
            'deviceID' => $user?->device_id ?? null,
            'parent_email' => $user?->profile?->parent_email ?? null,
            'parent_phone' => $user?->profile?->parent_phone ?? null,
        ];
    }

    public function getUserAppList(array|string|null $queryParams = null, int $perPage = 15)
    {
        $query = $this->customAdvancedCursorPaginate($queryParams, $perPage, function ($query) use ($queryParams) {
            $search = $queryParams['search'] ?? null;
            $name = $queryParams['name'] ?? null;
            $email = $queryParams['email'] ?? null;
            $role = $queryParams['role'] ?? null;
            $app = $queryParams['app'] ?? null;

            $query = $query
                ->select([
                    'users.*',
                    'user_apps.app as user_app',
                    'user_apps.plan',
                    'user_apps.plan_started_at',
                    'user_apps.plan_expires_at'
                ])
                ->leftJoin('user_apps', 'users.id', '=', 'user_apps.user_id')
                ->with([
                    'profile' => function ($query) {
                        $query->select(['user_id', 'phone']);
                    }
                ]);

            // Apply filters
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('user_profiles.phone', 'like', "%{$search}%");
                });
            }

            if ($name) {
                $query->where('users.name', 'like', "%{$name}%");
            }

            if ($email) {
                $query->where('users.email', 'like', "%{$email}%");
            }

            if ($role) {
                $query->where('users.role', $role);
            }

            if ($app) {
                $query->where('user_apps.app', $app);
            }

            return $query;
        });

        $paginator = $query->cursorPaginate($perPage);

        return [
            'data' => $paginator->items(),
            'pagination' => PaginationUtils::formatCursorPagination($paginator)
        ];
    }

    public function getUserByReferralCode($referral_code)
    {
        try {
            return $this->model->where('referral_code', $referral_code)->select(['id'])->firstOrFail();
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'referral_code' => 'User with referral code \'' . $referral_code . '\' not found',
            ]);
        }
    }
}
