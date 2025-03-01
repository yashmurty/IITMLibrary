<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class AdminAuthenticate
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
        $user = Auth::user();

        if (!$user) {
            return redirect('login');
        }

        // Get user_authRoles from the shared view data
        $userAuthRoles = view()->shared('user_authRoles', []);

        if (in_array('admin', $userAuthRoles)) {
            return $next($request);
        } else {
            return redirect('home')
                ->with('globalalertmessage', 'You do not have admin privileges')
                ->with('globalalertclass', 'error');
        }
    }
}
