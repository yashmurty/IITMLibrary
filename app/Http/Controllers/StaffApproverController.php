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
}
