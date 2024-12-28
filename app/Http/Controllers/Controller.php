<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\ApiResponse;
use App\Traits\HandlesErrors;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ApiResponse, HandlesErrors;

    protected function getExamTypeFromHeader(Request $request)
    {
        return $request->header('MFA_EXAM') ?? SetupConstant::$exams[2];

    }
}
