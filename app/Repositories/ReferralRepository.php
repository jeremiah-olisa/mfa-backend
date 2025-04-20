<?php

namespace App\Repositories;

use App\Models\Referral;
use App\Repositories\BaseRepository;

class ReferralRepository extends BaseRepository
{
    public function __construct(Referral $model)
    {
        parent::__construct($model);
    }
}
