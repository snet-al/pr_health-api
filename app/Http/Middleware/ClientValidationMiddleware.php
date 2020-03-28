<?php

namespace App\Http\Middleware;

use App\Traits\ApiTrait;
use Closure;

class ClientValidationMiddleware
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
        if (! $request->filled('client')) {
            return $this->restApiGeneralErrorResponse([__('clients.element_required')]);
        }

        $user = auth()->user();

        $client = $user->client;
        if (! $client) {
            return $this->resourceNotFound([__('clients.not_found')]);
        }

        if (! $user->is_active) {
            return $this->resourceNotActive([__('users.not_active')]);
        }

        $request['request_client'] = $request->get('client');
        $request['client'] = $client;
        $request['user'] = $user;

        return $next($request);
    }
}
