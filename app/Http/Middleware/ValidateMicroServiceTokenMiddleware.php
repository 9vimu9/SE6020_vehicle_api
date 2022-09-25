<?php

namespace App\Http\Middleware;

use App\Services\MicroServiceToken\TokenValidator;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateMicroServiceTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token= $request->bearerToken();
        if(!$token){
            abort(403, 'Access denied no bearer token found');
        }
        $tokenValidator = new TokenValidator($token);
        if(!$tokenValidator->validate()){
            abort(403, 'Invalid token');
        }
        $tokenValidator->saveUserFromToken();
        return $next($request);
    }
}
