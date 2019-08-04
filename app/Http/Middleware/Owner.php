<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::getRoleNames()->first() == "superadmin" || !Auth::getRoleNames()->first() == "admin" || !Auth::getRoleNames()->first() == "hr") {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return $next($request);
    }
}
