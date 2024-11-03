<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->github_id || $user->google_id || $user->facebook_id) {
            return $next($request);
        }

        if($user->status && $user->email_verification && $user->sms_verification && $user->two_fa_verify){
            return $next($request);
        }
        return redirect(route('api.user.check'));
    }
}
