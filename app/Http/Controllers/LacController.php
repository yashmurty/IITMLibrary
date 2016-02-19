<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use Input;
use Validator;
use App\BasicRequisitionForm;
use Mail;
use App\User;


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
        $validator = Validator::make(Input::all(), [
            'brf_id'    => 'required',
            'lac_status'=> 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/lac/requeststatus')
                ->withInput()
                ->withErrors($validator)
                ->with('globalalertmessage', 'Failed to Aprove. Try Again')
                ->with('globalalertclass', 'error');
        }

        $brf_model_instance = BasicRequisitionForm::find(Input::get('brf_id'));
        $brf_model_instance->lac_status = Input::get('lac_status');
        $brf_model_instance->remarks = Input::get('remarks');
        $brf_model_instance->save();

        if (Input::get('lac_status') == "denied") {
            # code...
            $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

            Mail::send('emails.deniedbylibrarian', 
                [
                    'brf_model_instance'        => $brf_model_instance,
                    'brf_model_user_instance'   => $brf_model_user_instance
                ], 
                function ($m) use ($brf_model_instance, $brf_model_user_instance) {
                $m->from('no-reply@iitm.ac.in', 'Library Portal Team');
                // $m->to($brf_model_user_instance->email, $brf_model_user_instance->name)->subject('[Library] Request Denied for Book');
                $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name)->subject('[Library] Request Denied for Book');
            });
        }

        return redirect('lac/requeststatus')
                ->with('globalalertmessage', 'Request Successfully updated.')
                ->with('globalalertclass', 'success');
    }




}
