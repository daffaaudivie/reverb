<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        if (!auth()->user()->hasRole(UserRole::ADMIN)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}