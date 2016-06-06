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
use Excel;
use Mail;
use App\User;
use DateTime;
use Carbon\Carbon;

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

        if (Input::get('librarian_status') == "denied") {
            # code...
            $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

            $lac_user_instance = DB::table('lac_users')
                                    ->where('iitm_dept_code', '=', $brf_model_instance->iitm_dept_code)
                                    ->first();

            // return $lac_user_instance;

            Mail::send('emails.deniedbylibrarian',
                [
                    'brf_model_instance'        => $brf_model_instance,
                    'brf_model_user_instance'   => $brf_model_user_instance,
                    'lac_user_instance'         => $lac_user_instance
                ],
                function ($m) use ($brf_model_instance, $brf_model_user_instance, $lac_user_instance) {
                $m->from('no-reply@iitm.ac.in', 'Library Portal Team');
                $m->to($brf_model_user_instance->email, $brf_model_user_instance->name)->subject('[Library] Request Denied for Book');
                // $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name);
                $m->cc($lac_user_instance->lac_email_id, $lac_user_instance->name)->subject('[Library] Request Denied for Book');
                // $m->cc("test@smail.iitm.ac.in", "X LAC Member");
                $m->subject('[Library] Request Denied for Book');
            });
        }

        return redirect('admin/requeststatus')
                ->with('globalalertmessage', 'Request Successfully updated.')
                ->with('globalalertclass', 'success');
    }

    public function getAdminRequestStatusExportExcel()
    {

        $admin_user_brfs = DB::table('brfs')
                        ->where('lac_status', "approved")
                        ->where('librarian_status', "approved")
                        ->where('download_status', null)
                        ->orderBy('id', 'desc')
                        ->get();

        // dd($admin_user_brfs);

        if(!empty($admin_user_brfs)){

            foreach ($admin_user_brfs as $key => $admin_user_brf) {

                $brf_model_instance = BasicRequisitionForm::find($admin_user_brf->id);
                $brf_model_instance->download_status = "downloaded";
                $brf_model_instance->remarks = "The procurement process has been initiated.";
                $brf_model_instance->save();

                $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

                Mail::send('emails.acceptedbylibrarian',
                    [
                        'brf_model_instance'        => $brf_model_instance,
                        'brf_model_user_instance'   => $brf_model_user_instance
                    ],
                    function ($m) use ($brf_model_instance, $brf_model_user_instance) {
                    $m->from('no-reply@iitm.ac.in', 'Library Portal Team');
                    $m->to($brf_model_user_instance->email, $brf_model_user_instance->name)->subject('[Library] Request Approved for Book');
                    // $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name)->subject('[Library] Request Approved for Book');
                    // No commented test for CC here.

                });

                $brf_array_row = (array) $admin_user_brf;
                $brf_array[] = $brf_array_row;
            }
            $data = (array) $brf_array;
            $date = Carbon::now();
            $currentDateTime = $date->toDateTimeString();

            Excel::create($currentDateTime, function($excel) use($data) {

                $excel->sheet('Sheetname', function($sheet) use($data) {

                    $sheet->fromArray($data);

                });

            })->export('xls');

            return "Excel Exported";

        } else {
            return redirect('admin/requeststatus')
                ->with('globalalertmessage', 'No requests found to be exported')
                ->with('globalalertclass', 'error');
        }



    }

    public function getAdminLACMembers()
    {

        $lac_users = DB::table('lac_users')
                        ->orderBy('iitm_dept_code', 'asc')
                        ->get();
        if(!empty($lac_users)){

            return view('admin.adminlacmembers')
                    ->with('lac_users', $lac_users);

        } else {
            // return "No Requests Found";
            return view('admin.adminlacmembers')
                    ->with('lac_users', null);
        }

    }

    public function getAdminLACMembersEdit($iitm_dept_code)
    {
        $lac_user = DB::table('lac_users')
                        ->where('iitm_dept_code', $iitm_dept_code)
                        ->first();
        dd($lac_user);
        return $iitm_dept_code;
    }

    public function getAdminStaffMembers()
    {

        $admin_users = DB::table('admin_users')
                        ->orderBy('id', 'asc')
                        ->get();
        if(!empty($admin_users)){

            return view('admin.adminstaffmembers')
                    ->with('admin_users', $admin_users);

        } else {
            // return "No Requests Found";
            return view('admin.adminstaffmembers')
                    ->with('admin_users', null);
        }

    }


}
