<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MinistryHeadMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'ministry_head'])) {
            abort(403);
        }
        return $next($request);
    }
}


