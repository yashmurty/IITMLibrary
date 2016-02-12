<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AdminController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminauth');
    }

    public function getHome()
    {

    	return view('admin.home');
    }

    // Request Status
    public function getAdminRequestStatus()
    {

        $admin_user_brfs = DB::table('brfs')
                        ->where('lac_status', "approved")
                        ->orderBy('id', 'desc')
                        ->get();
        if(!empty($admin_user_brfs)){
    
            return view('admin.adminrequeststatus')
                    ->with('admin_user_brfs', $admin_user_brfs);

        } else {
            // return "No Requests Found";
            return view('admin.adminrequeststatus')
                    ->with('admin_user_brfs', null);
        }

    }


}
