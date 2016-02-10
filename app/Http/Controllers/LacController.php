<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use Input;

class LacController extends Controller
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
        $this->middleware('lacauth');
    }

    public function getHome()
    {

        

    	return view('lac.home');
    }

    // Request Status
    public function getLacRequestStatus()
    {
        $iitm_dept_code = Auth::user()->iitm_dept_code;

        $lac_user_brfs = DB::table('brfs')
                        ->where('iitm_dept_code', $iitm_dept_code)
                        ->orderBy('id', 'desc')
                        ->get();
        if(!empty($lac_user_brfs)){
    
            return view('lac.lacrequeststatus')
                    ->with('lac_user_brfs', $lac_user_brfs);

        } else {
            // return "No Requests Found";
            return view('lac.lacrequeststatus')
                    ->with('lac_user_brfs', null);
        }

    }

    // Request Status View BRF
    public function getLacRequestStatusViewBRF($brf_id)
    {
        $iitm_dept_code = Auth::user()->iitm_dept_code;

        $lac_user_brf = DB::table('brfs')
                        ->where('iitm_dept_code', $iitm_dept_code)
                        ->where('id', $brf_id)
                        ->orderBy('id', 'desc')
                        ->first();

        // dd($lac_user_brf);

        if(!empty($lac_user_brf)){
    
            return view('lac.lacrequeststatusviewbrf')
                    ->with('lac_user_brf', $lac_user_brf);

        } else {
            // return "No Requests Found";
            return view('lac.lacrequeststatusviewbrf')
                    ->with('lac_user_brf', null);
        }
    }

    public function postLacRequestStatusApproveBRF()
    {
        return Input::all();
    }



    
}
