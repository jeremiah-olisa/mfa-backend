<?php

namespace App\Repositories;

use App\Models\Referral;
use App\Repositories\BaseRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ReferralRepository extends BaseRepository
{
    public function __construct(Referral $model)
    {
        parent::__construct($model);
    }

    public function checkIfUserHasBeenReferred($newUserId)
    {
        return $this->model->where('referred_id', $newUserId)->exists();
    }


    /**
     * Create a new referral record
     *
     * @param string $referralCode
     * @param int $newUserId
     * @return Referral
     * @throws \Exception
     */
    public function createReferral(string $referrerId, int $newUserId, $referralCode): Referral
    {
        // Prevent self-referrals
        if ($referrerId === $newUserId) {
            throw new BadRequestException('Cannot refer yourself');
        }

        // Check if this user was already referred
        if ($this->checkIfUserHasBeenReferred($newUserId)) {
            throw new BadRequestException('This user already has a referral');
        }

        return parent::create([
            'referrer_id' => $referrerId,
            'referred_id' => $newUserId,
            'code' => $referralCode
        ]);
    }
}
