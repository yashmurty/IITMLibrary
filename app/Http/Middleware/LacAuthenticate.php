<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class LacAuthenticate
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

        //return Auth::user();
        $iitm_id = Auth::user()->iitm_id;

        // $BasicProfileData = BasicProfile::where('api_userId', '=', 1);
        $lac_user = DB::table('lac_users')
            ->where('iitm_id', '=', $iitm_id)
            ->first();
        if (!empty($lac_user)) {
            return $next($request);
        } else {
            // return "You are not an Admin";
            return redirect('home')
                ->with('globalalertmessage', 'You are not an LAC Member')
                ->with('globalalertclass', 'error');
        }
    }
}
