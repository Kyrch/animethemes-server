<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Concerns\DetectsRedis;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Class ThrottleRequestsWithService.
 */
class ThrottleRequestsWithService
{
    use DetectsRedis;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): mixed  $next
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     * @param  string  $prefix
     * @return mixed
     */
    public function handle(
        Request $request,
        Closure $next,
        int|string $maxAttempts = 60,
        float|int $decayMinutes = 1,
        string $prefix = ''
    ): mixed {
        // Throttle with Redis if configured, else use default throttling middleware
        $middleware = $this->appUsesRedis()
            ? App::make(ThrottleRequestsWithRedis::class)
            : App::make(ThrottleRequests::class);

        // Use named limiter if configured, else use default handling
        // Note: framework requires that we pass exactly 3 arguments to use named limiter
        if (RateLimiter::limiter($maxAttempts) !== null) {
            return $middleware->handle($request, $next, $maxAttempts);
        }

        return $middleware->handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
