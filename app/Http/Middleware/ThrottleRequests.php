<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequest;

class ThrottleRequests extends BaseThrottleRequest
{
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $response = response()->error(trans('auth.throttle', ['seconds' => $retryAfter]), [],  429);

        return parent::buildException($request, $key, $maxAttempts, function () use ($response) {
            return $response;
    });
    }
}
