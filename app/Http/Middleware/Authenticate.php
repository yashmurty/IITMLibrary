<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        $iitm_id = Auth::user()->iitm_id;
        $userRoles = [];
        $userType = null;

        // Check for LAC user
        $isLacUser = DB::table('lac_users')
            ->where('iitm_id', '=', $iitm_id)
            ->exists();

        if ($isLacUser) {
            $userRoles[] = 'lac';
        }

        // Check for Admin user
        $adminUser = DB::table('admin_users')
            ->where('iitm_id', '=', $iitm_id)
            ->first();

        if (!empty($adminUser)) {
            if ($adminUser->role === config('roles.admin')) {
                $userRoles[] = 'admin';
                $userRoles[] = 'staff_approver'; // Admin always has staff_approver role
            } elseif ($adminUser->role === config('roles.staff_approver')) {
                $userRoles[] = 'staff_approver';
            } else {
                $userRoles[] = 'unknown_admin';
            }
        }

        // If no role assigned yet, user is faculty
        if (empty($userRoles)) {
            $userRoles[] = 'faculty';
        }

        // Determine legacy auth_usertype (first priority role)
        if (in_array('lac', $userRoles)) {
            $userType = 'lac';
        } elseif (in_array('admin', $userRoles)) {
            $userType = 'admin';
        } elseif (in_array('staff_approver', $userRoles)) {
            $userType = 'staff_approver';
        } elseif (in_array('unknown_admin', $userRoles)) {
            $userType = 'unknown_admin';
        } else {
            $userType = 'faculty';
        }

        // Share variables with all views
        view()->share('auth_usertype', $userType);
        view()->share('user_authRoles', $userRoles);

        return $next($request);
    }
}
