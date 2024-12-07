<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    public function api_response(string $message, array|int|string|null $data = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        $statusText = Response::$statusTexts[$code] ?? 'Unknown status';
        $status = $code . " " . $statusText;
        return response()->json(array_merge(['status' => $status, 'message' => $message], $data ?? []), $code);
    }

    public function paginated_response($response): array
    {
        $res = json_decode(json_encode($response));
        $data = data_get($res, 'data');
        $paginated = data_forget($res, 'data');

        return [$data, $paginated];
    }
}
