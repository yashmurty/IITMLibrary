<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Input;
use Auth;
use App\BasicRequisitionForm;


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

    // Request Status View BRF
    public function getAdminRequestStatusViewBRF($brf_id)
    {
        $iitm_dept_code = Auth::user()->iitm_dept_code;

        $admin_user_brf = DB::table('brfs')
                        ->where('lac_status', "approved")
                        ->where('id', $brf_id)
                        ->orderBy('id', 'desc')
                        ->first();

        // dd($lac_user_brf);

        if(!empty($admin_user_brf)){
    
            return view('admin.adminrequeststatusviewbrf')
                    ->with('admin_user_brf', $admin_user_brf);

        } else {
            // return "No Requests Found";
            return view('admin.adminrequeststatusviewbrf')
                    ->with('admin_user_brf', null);
        }
    }

    public function postAdminRequestStatusApproveBRF()
    {
        $validator = Validator::make(Input::all(), [
            'brf_id'    => 'required',
            'librarian_status'=> 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/requeststatus')
                ->withInput()
                ->withErrors($validator)
                ->with('globalalertmessage', 'Failed to Aprove. Try Again')
                ->with('globalalertclass', 'error');
        }

        $brf_model_instance = BasicRequisitionForm::find(Input::get('brf_id'));
        $brf_model_instance->librarian_status = Input::get('librarian_status');
        $brf_model_instance->remarks = Input::get('remarks');
        $brf_model_instance->save();

        return redirect('admin/requeststatus')
                ->with('globalalertmessage', 'Request Successfully updated.')
                ->with('globalalertclass', 'success');
    }


}
