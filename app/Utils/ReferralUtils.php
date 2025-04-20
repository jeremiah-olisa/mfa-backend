<?php

namespace App\Utils;

class ReferralUtils
{
    public static function getReferralId(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 5));
        } while (\App\Models\User::where('referral_code', $code)->exists());
        return $code;
    }
}