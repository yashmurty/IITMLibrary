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
use Carbon\Carbon;

class StaffApproverController extends Controller
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
        $this->middleware('staff.approver');
    }

    public function getHome()
    {

        return view('admin.home');
    }

    // Request Status
    public function getStaffAdminRequestStatus()
    {

        $user_brfs = DB::table('brfs')
            ->where('lac_status', "approved")
            ->where('librarian_status', NULL)
            ->orWhere('librarian_status', 'approved')
            ->where('download_status', NULL)
            ->orderBy('id', 'desc')
            ->get();
        if (!empty($user_brfs)) {

            return view('staff-approver.requeststatus')
                ->with('user_brfs', $user_brfs);
        } else {
            // return "No Requests Found";
            return view('staff-approver.requeststatus')
                ->with('user_brfs', null);
        }
    }

    // Request Status View BRF
    public function getStaffAdminRequestStatusViewBRF($brf_id)
    {
        $admin_user_brf = DB::table('brfs')
            ->where('lac_status', "approved")
            ->where('id', $brf_id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($lac_user_brf);

        if (!empty($admin_user_brf)) {

            return view('staff-approver.requeststatus-view-brf')
                ->with('admin_user_brf', $admin_user_brf);
        } else {
            // return "No Requests Found";
            return view('staff-approver.requeststatus-view-brf')
                ->with('admin_user_brf', null);
        }
    }

    public function postStaffAdminRequestStatusApproveBRF()
    {
        $validator = Validator::make(Input::all(), [
            'brf_id'    => 'required',
            'librarian_status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/staff-approver/requeststatus')
                ->withInput()
                ->withErrors($validator)
                ->with('globalalertmessage', 'Failed to Aprove. Try Again')
                ->with('globalalertclass', 'error');
        }

        $brf_model_instance = BasicRequisitionForm::find(Input::get('brf_id'));
        $brf_model_instance->librarian_status = Input::get('librarian_status');
        $brf_model_instance->remarks = Input::get('remarks');
        $brf_model_instance->save();

        if (Input::get('librarian_status') == "denied") {
            # code...
            $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

            $lac_user_instance = DB::table('lac_users')
                ->where('iitm_dept_code', '=', $brf_model_instance->iitm_dept_code)
                ->first();

            // return $lac_user_instance;

            Mail::send(
                'emails.deniedbylibrarian',
                [
                    'brf_model_instance'        => $brf_model_instance,
                    'brf_model_user_instance'   => $brf_model_user_instance,
                    'lac_user_instance'         => $lac_user_instance
                ],
                function ($m) use ($brf_model_instance, $brf_model_user_instance, $lac_user_instance) {
                    $m->from('librarian@iitm.ac.in', 'Library Portal Team');
                    $m->to($brf_model_user_instance->email, $brf_model_user_instance->name)->subject('[Library] Request Denied for Book');
                    // $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name);
                    $m->cc($lac_user_instance->lac_email_id, $lac_user_instance->name)->subject('[Library] Request Denied for Book');
                    // $m->cc("test@smail.iitm.ac.in", "X LAC Member");
                    $m->subject('[Library] Request Denied for Book');
                }
            );
        }

        return redirect('staff-approver/requeststatus')
            ->with('globalalertmessage', 'Request Successfully updated.')
            ->with('globalalertclass', 'success');
    }

    // Edit BRF - Remarks, etc.
    public function putStaffAdminRequestStatusEditBRF($brf_id)
    {
        $brf_model_instance = BasicRequisitionForm::find($brf_id);
        $brf_model_instance->remarks = Input::get('edit-remarks');
        $brf_model_instance->save();
        // dd($admin_user_brf);

        return redirect('staff-approver/requeststatus/brf/' . $brf_id)
            ->with('globalalertmessage', 'Request Successfully updated.')
            ->with('globalalertclass', 'success');
    }
}
