<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class StaffApproverAuthenticate
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

        // Assuming user_authRoles is set by Authenticate middleware
        // We can use view()->shared() to retrieve it
        $userAuthRoles = view()->shared('user_authRoles', []);

        if (in_array('staff_approver', $userAuthRoles)) {
            return $next($request);
        } else {
            return redirect('home')
                ->with('globalalertmessage', 'You do not have staff approver privileges')
                ->with('globalalertclass', 'error');
        }
    }
}
