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

        $lac_user = DB::table('lac_users')
                        ->where('iitm_id', '=', $iitm_id)
                        ->first();

        $admin_user = DB::table('admin_users')
                        ->where('iitm_id', '=', $iitm_id)
                        ->first();

        if (!empty($lac_user)) {
            view()->share('auth_usertype', 'lac');
        } elseif (!empty($admin_user)) {
            view()->share('auth_usertype', 'admin');
        } else {
            view()->share('auth_usertype', 'faculty');
        }

        return $next($request);
    }
}
