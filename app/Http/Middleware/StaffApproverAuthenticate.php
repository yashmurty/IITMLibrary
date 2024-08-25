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

        $staff_approver = DB::table('admin_users')
            ->where('iitm_id', '=', $user->iitm_id)
            ->first();

        if ($staff_approver && $staff_approver->role === config('roles.staff_approver')) {
            return $next($request);
        } else {
            return redirect('home')
                ->with('globalalertmessage', 'You do not have staff approver privileges')
                ->with('globalalertclass', 'error');
        }
    }
}
