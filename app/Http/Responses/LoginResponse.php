<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Enums\UserRole;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();
        
        if ($user->hasRole(UserRole::ADMIN)) {
            return redirect()->intended('/admin/dashboard');
        }
        
        if ($user->hasRole(UserRole::USER)) {
            return redirect()->intended('/dashboard');
        }
        
        return redirect()->intended(config('fortify.home'));
    }
}