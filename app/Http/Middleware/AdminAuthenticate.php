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

        $admin_user = DB::table('admin_users')
            ->where('iitm_id', '=', $user->iitm_id)
            ->first();

        if ($admin_user && $admin_user->role === config('roles.admin')) {
            return $next($request);
        } else {
            return redirect('home')
                ->with('globalalertmessage', 'You do not have admin privileges')
                ->with('globalalertclass', 'error');
        }
    }
}
