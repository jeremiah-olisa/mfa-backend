<?php

namespace App\Constants;

class SetupConstant
{
    public static $exams = ['WAEC', 'NECO', 'JAMB'];
    public static $apps = ['WAEC', 'NECO', 'JAMB', 'OYO', 'WEB', 'ADMIN'];
    public static $roles = ['Student', 'Admin', 'Content Manager'];

    public static $appExams = [
        'WAEC' => 'WAEC',
        'NECO' => 'NECO',
        'JAMB' => 'JAMB',
        'OYO' => 'JAMB',
        'WEB' => null,
        'ADMIN' => null,
    ];

    public static function getExamByApp(string $app)
    {
        return self::$appExams[$app] ?? null;
    }

    public static $oAuthBaseUrls = [
        'WAEC' => 'https://myfirstattempt.com',
        'NECO' => 'https://myfirstattempt.com',
        'JAMB' => 'https://myfirstattempt.com',
        'WEB' => 'https://myfirstattempt.com',
        'ADMIN' => 'https://myfirstattempt.com',
        'OYO' => 'https://myfirstattempt.com/oyo',
    ];
}
