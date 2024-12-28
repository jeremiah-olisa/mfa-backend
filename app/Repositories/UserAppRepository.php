<?php

namespace App\Repositories;

use App\Models\UserApp;
use Carbon\Carbon;

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

    public function updateExpiry(int $userId, int $duration, string $plan, string $app)
    {
        // Retrieve the user app by user ID and app
        $userApp = $this->model->newQuery()
            ->where('user_id', $userId)
            ->where('app', $app)
            ->first();

        // If the app does not exist, create it
        if (!$userApp) {
            $userApp = $this->model->newQuery()->create([
                'user_id' => $userId,
                'app' => $app,
                'plan_expires_at' => now()->addDays($duration),
                'plan' => $plan,
            ]);

            return $userApp->plan_expires_at;
        }

        // Update the expiry date if the app exists
        $currentExpiry = $userApp->plan_expires_at;

        if ($currentExpiry && Carbon::parse($currentExpiry)->isFuture()) {
            $userApp->plan_expires_at = Carbon::parse($currentExpiry)->addDays($duration);
        } else {
            $userApp->plan_expires_at = now()->addDays($duration);
        }

        $userApp->plan = $plan;
        $userApp->save();

        return $userApp->plan_expires_at;
    }

}
