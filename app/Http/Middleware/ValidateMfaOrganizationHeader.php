<?php

namespace App\Http\Middleware;

use App\Constants\SetupConstant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ValidateMfaOrganizationHeader
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mfaOrganizationHeader = $request->header('MFA_ORGANIZATION');

        if (!$request->wantsJson())
            return $next($request);

        if (!$mfaOrganizationHeader || !in_array($mfaOrganizationHeader, SetupConstant::$apps))
            throw ValidationException::withMessages([
                'header'  => 'Invalid MFA_ORGANIZATION header'
            ]);

        // Get the corresponding MFA_EXAM based on MFA_ORGANIZATION
        $mfaExam = SetupConstant::getExamByApp($mfaOrganizationHeader);

        // Add MFA_EXAM to the request headers
        if ($mfaExam) $request->headers->set('MFA_EXAM', $mfaExam);

        return $next($request);
    }
}
