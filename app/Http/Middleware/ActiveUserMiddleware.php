<?php

namespace App\Http\Middleware;

use App\Helpers\ApiCodes;
use App\Traits\ApiTrait;
use Closure;

class ActiveUserMiddleware
{
    use ApiTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! \Auth::check() || ! \Auth::user()->isActive()) {
            return $this->resourceNotActive(__('users.not_active'));

            response()->json([
                    'message' => 'The user is not active.',
                    'status' => ApiCodes::RESOURCE_INACTIVE,
                    'data' => [],
                    'errors' => __('users.not_active'),
            ], 401);
        }

        return $next($request);
    }
}
