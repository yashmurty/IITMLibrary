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
                        ->where('librarian_status', NULL)
                        ->orWhere('librarian_status', 'approved')
                        ->where('download_status', NULL)
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
                $m->from('librarian@iitm.ac.in', 'Library Portal Team');
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
                    $m->from('librarian@iitm.ac.in', 'Library Portal Team');
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

    // Approved by LAC but pending Librarian approval
    public function getAdminPendingRequestStatusExportExcel()
    {

        $admin_user_brfs = DB::table('brfs')
                        ->where('lac_status', "approved")
                        ->where('librarian_status', null)
                        ->where('download_status', null)
                        ->orderBy('id', 'desc')
                        ->get();

        // dd($admin_user_brfs);

        if(!empty($admin_user_brfs)){

            foreach ($admin_user_brfs as $key => $admin_user_brf) {

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

            return "Pending Status Excel Exported";

        } else {
            return redirect('admin/requeststatus')
                ->with('globalalertmessage', 'No requests found to be exported')
                ->with('globalalertclass', 'error');
        }



    }

    // Request Status - Archived
    public function getAdminRequestStatusArchived()
    {
        $admin_user_brfs = DB::table('brfs')
                        ->where('lac_status', "approved")
                        ->where('librarian_status', "approved")
                        ->where('download_status', "downloaded")
                        ->orderBy('id', 'desc')
                        ->get();
        if(!empty($admin_user_brfs)){

            return view('admin.adminrequeststatus-archived')
                    ->with('admin_user_brfs', $admin_user_brfs);

        } else {
            // return "No Requests Found";
            return view('admin.adminrequeststatus-archived')
                    ->with('admin_user_brfs', null);
        }

    }

    public function getAdminRequestStatusArchivedStatusYear($archived_status, $year_from_until)
    {
      $years = explode("-", $year_from_until);
      $year_from = $years[0];
      $year_untill = $years[1];

      switch ($archived_status) {
        case 'approved':
          $admin_user_brfs = DB::table('brfs')
                          ->where('lac_status', "approved")
                          ->where('librarian_status', "approved")
                          ->where('download_status', "downloaded")
                          ->whereDate('created_at', '>=', $years[0].'-04-01')
                          ->whereDate('created_at', '<=', $years[1].'-03-31')
                          ->orderBy('id', 'desc')
                          ->get();
          break;

        case 'denied':
          $admin_user_brfs = DB::table('brfs')
                          ->where('lac_status', "approved")
                          ->where('librarian_status', "denied")
                          ->whereDate('created_at', '>=', $years[0].'-04-01')
                          ->whereDate('created_at', '<=', $years[1].'-03-31')
                          ->orderBy('id', 'desc')
                          ->get();
          break;

      }


      if(!empty($admin_user_brfs)){

          return view('admin.adminrequeststatus-archived-status-year')
                  ->with('archived_status', $archived_status)
                  ->with('year_from', $year_from)
                  ->with('year_until', $year_untill)
                  ->with('admin_user_brfs', $admin_user_brfs);

      } else {
          // return "No Requests Found";
          return view('admin.adminrequeststatus-archived-status-year')
                  ->with('archived_status', $archived_status)
                  ->with('year_from', $year_from)
                  ->with('year_until', $year_untill)
                  ->with('admin_user_brfs', null);
      }
    }

    // GET LAC Memebers
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
        if(!empty($lac_user)){

            return view('admin.admin-lacmembers-edit')
                    ->with('lac_user', $lac_user);

        } else {
            // return "No Requests Found";
            return view('admin.admin-lacmembers-edit')
                    ->with('lac_user', null);
        }
    }

    public function postAdminLACMembersEdit($iitm_dept_code)
    {
        // return Input::all();
        $iitm_id = Input::get('input_iitm_id');
        $name               = Input::get('input_name');
        $lac_email_id       = Input::get('input_lac_email_id');

        $lac_user = DB::table('lac_users')
                        ->where('iitm_dept_code', $iitm_dept_code)
                        ->update(
                                    [
                                        'iitm_id'       => $iitm_id,
                                        'name'          => $name,
                                        'lac_email_id'  => $lac_email_id
                                    ]);
        return redirect('/admin/lacmembers');
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

    public function getAdminBRFAnalytics()
    {
        $brf_all_count = BasicRequisitionForm::all()->count();
        // Requests that are pending but have been approved by LAC Members
        $brf_pending_lac_approved_count = BasicRequisitionForm::where('lac_status', "approved")->where('librarian_status', NULL)->count();
        // Requests that are pending but have been approved by LAC Members and Librarian
        $brf_pending_librarian_approved_count = BasicRequisitionForm::where('lac_status', "approved")->where('librarian_status', "approved")->where('download_status', NULL)->count();
        // Requests that have been Successfully downloaded and approved
        $brf_approved_downloaded_count = BasicRequisitionForm::where('lac_status', "approved")->where('librarian_status', "approved")->where('download_status', "downloaded")->count();

        // Requests that have been Denied by LAC Members
        $brf_pending_lac_denied_count = BasicRequisitionForm::where('lac_status', "denied")->count();
        // Requests that have been Denied by Librarian
        $brf_pending_librarian_denied_count = BasicRequisitionForm::where('lac_status', "approved")->where('librarian_status', "denied")->count();

        // Requests that are new and pending LAC Member Approval
        $brf_new_pending_lac_count = BasicRequisitionForm::where('lac_status', NULL)->count();

        return view('admin.admin-brf-analytics')
                ->with('brf_all_count', $brf_all_count)
                ->with('brf_pending_lac_approved_count', $brf_pending_lac_approved_count)
                ->with('brf_pending_librarian_approved_count', $brf_pending_librarian_approved_count)
                ->with('brf_approved_downloaded_count', $brf_approved_downloaded_count)
                ->with('brf_pending_lac_denied_count', $brf_pending_lac_denied_count)
                ->with('brf_pending_librarian_denied_count', $brf_pending_librarian_denied_count)
                ->with('brf_new_pending_lac_count', $brf_new_pending_lac_count);
    }

    public function getAdminBRFAnalyticsYear($year_from_until)
    {
        $years = explode("-", $year_from_until);
        $year_from = $years[0];
        $year_untill = $years[1];

        // return $year_from;
        $iitm_dept_code = null;
        $lac_users_departments = DB::table('lac_users')
                                    ->get();

        $brf_all_count = BasicRequisitionForm::all()->count();
        // Requests that are pending but have been approved by LAC Members
        $brf_pending_lac_approved_count = BasicRequisitionForm::where('lac_status', "approved")
                                            ->where('librarian_status', NULL)
                                            ->whereDate('created_at', '>=', $years[0].'-04-01')
                                            ->whereDate('created_at', '<=', $years[1].'-03-31')
                                            ->count();
        // Requests that are pending but have been approved by LAC Members and Librarian
        $brf_pending_librarian_approved_count = BasicRequisitionForm::where('lac_status', "approved")
                                                ->where('librarian_status', "approved")
                                                ->where('download_status', NULL)
                                                ->whereDate('created_at', '>=', $years[0].'-04-01')
                                                ->whereDate('created_at', '<=', $years[1].'-03-31')
                                                ->count();
        // Requests that have been Successfully downloaded and approved
        $brf_approved_downloaded_count = BasicRequisitionForm::where('lac_status', "approved")
                                            ->where('librarian_status', "approved")
                                            ->where('download_status', "downloaded")
                                            ->whereDate('created_at', '>=', $years[0].'-04-01')
                                            ->whereDate('created_at', '<=', $years[1].'-03-31')
                                            ->count();

        // Requests that have been Denied by LAC Members
        $brf_pending_lac_denied_count = BasicRequisitionForm::where('lac_status', "denied")
                                            ->whereDate('created_at', '>=', $years[0].'-04-01')
                                            ->whereDate('created_at', '<=', $years[1].'-03-31')
                                            ->count();
        // Requests that have been Denied by Librarian
        $brf_pending_librarian_denied_count = BasicRequisitionForm::where('lac_status', "approved")
                                                ->where('librarian_status', "denied")
                                                ->whereDate('created_at', '>=', $years[0].'-04-01')
                                                ->whereDate('created_at', '<=', $years[1].'-03-31')
                                                ->count();

        // Requests that are new and pending LAC Member Approval
        $brf_new_pending_lac_count = BasicRequisitionForm::where('lac_status', NULL)
                                        ->whereDate('created_at', '>=', $years[0].'-04-01')
                                        ->whereDate('created_at', '<=', $years[1].'-03-31')
                                        ->count();

        // User Analytics
        $users = DB::table('users')
                    ->get();
        foreach ($users as $key => $user) {
            $brf_requests_count = BasicRequisitionForm::where('iitm_id', $user->iitm_id)
                                    ->whereDate('created_at', '>=', $years[0].'-04-01')
                                    ->whereDate('created_at', '<=', $years[1].'-03-31')
                                    ->count();
            $user->brf_requests_count = $brf_requests_count;
        }
        usort($users, function($a, $b) { //Sort the array using a user defined function
            return $a->brf_requests_count > $b->brf_requests_count ? -1 : 1; //Compare the scores
        });

        return view('admin.admin-brf-analytics-year')
                ->with('iitm_dept_code', $iitm_dept_code)
                ->with('lac_users_departments', $lac_users_departments)
                ->with('brf_all_count', $brf_all_count)
                ->with('brf_pending_lac_approved_count', $brf_pending_lac_approved_count)
                ->with('brf_pending_librarian_approved_count', $brf_pending_librarian_approved_count)
                ->with('brf_approved_downloaded_count', $brf_approved_downloaded_count)
                ->with('brf_pending_lac_denied_count', $brf_pending_lac_denied_count)
                ->with('brf_pending_librarian_denied_count', $brf_pending_librarian_denied_count)
                ->with('brf_new_pending_lac_count', $brf_new_pending_lac_count)
                ->with('year_from', $year_from)
                ->with('year_until', $year_untill)
                ->with('users', $users);
    }

    public function getAdminBRFAnalyticsYearDepartment($year_from_until, $iitm_dept_code)
    {
        $years = explode("-", $year_from_until);
        $year_from = $years[0];
        $year_untill = $years[1];

        // return $year_from;
        $lac_users_departments = DB::table('lac_users')
                                    ->get();

        $brf_all_count = BasicRequisitionForm::all()->count();
        // Requests that are pending but have been approved by LAC Members
        $brf_pending_lac_approved_count = BasicRequisitionForm::where('lac_status', "approved")
                                            ->where('iitm_dept_code', $iitm_dept_code)
                                            ->where('librarian_status', NULL)
                                            ->whereDate('created_at', '>=', $years[0].'-04-01')
                                            ->whereDate('created_at', '<=', $years[1].'-03-31')
                                            ->count();
        // Requests that are pending but have been approved by LAC Members and Librarian
        $brf_pending_librarian_approved_count = BasicRequisitionForm::where('lac_status', "approved")
                                                ->where('iitm_dept_code', $iitm_dept_code)
                                                ->where('librarian_status', "approved")
                                                ->where('download_status', NULL)
                                                ->whereDate('created_at', '>=', $years[0].'-04-01')
                                                ->whereDate('created_at', '<=', $years[1].'-03-31')
                                                ->count();
        // Requests that have been Successfully downloaded and approved
        $brf_approved_downloaded_count = BasicRequisitionForm::where('lac_status', "approved")
                                            ->where('iitm_dept_code', $iitm_dept_code)
                                            ->where('librarian_status', "approved")
                                            ->where('download_status', "downloaded")
                                            ->whereDate('created_at', '>=', $years[0].'-04-01')
                                            ->whereDate('created_at', '<=', $years[1].'-03-31')
                                            ->count();

        // Requests that have been Denied by LAC Members
        $brf_pending_lac_denied_count = BasicRequisitionForm::where('lac_status', "denied")
                                            ->where('iitm_dept_code', $iitm_dept_code)
                                            ->whereDate('created_at', '>=', $years[0].'-04-01')
                                            ->whereDate('created_at', '<=', $years[1].'-03-31')
                                            ->count();
        // Requests that have been Denied by Librarian
        $brf_pending_librarian_denied_count = BasicRequisitionForm::where('lac_status', "approved")
                                                ->where('iitm_dept_code', $iitm_dept_code)
                                                ->where('librarian_status', "denied")
                                                ->whereDate('created_at', '>=', $years[0].'-04-01')
                                                ->whereDate('created_at', '<=', $years[1].'-03-31')
                                                ->count();

        // Requests that are new and pending LAC Member Approval
        $brf_new_pending_lac_count = BasicRequisitionForm::where('lac_status', NULL)
                                        ->where('iitm_dept_code', $iitm_dept_code)
                                        ->whereDate('created_at', '>=', $years[0].'-04-01')
                                        ->whereDate('created_at', '<=', $years[1].'-03-31')
                                        ->count();

        // User Analytics - Department-wise
        $users = DB::table('users')
                    ->where('iitm_dept_code', $iitm_dept_code)
                    ->get();
        foreach ($users as $key => $user) {
            $brf_requests_count = BasicRequisitionForm::where('iitm_id', $user->iitm_id)
                                    ->whereDate('created_at', '>=', $years[0].'-04-01')
                                    ->whereDate('created_at', '<=', $years[1].'-03-31')
                                    ->count();
            $user->brf_requests_count = $brf_requests_count;
        }
        usort($users, function($a, $b) { //Sort the array using a user defined function
            return $a->brf_requests_count > $b->brf_requests_count ? -1 : 1; //Compare the scores
        });

        return view('admin.admin-brf-analytics-year')
                ->with('iitm_dept_code', $iitm_dept_code)
                ->with('lac_users_departments', $lac_users_departments)
                ->with('brf_all_count', $brf_all_count)
                ->with('brf_pending_lac_approved_count', $brf_pending_lac_approved_count)
                ->with('brf_pending_librarian_approved_count', $brf_pending_librarian_approved_count)
                ->with('brf_approved_downloaded_count', $brf_approved_downloaded_count)
                ->with('brf_pending_lac_denied_count', $brf_pending_lac_denied_count)
                ->with('brf_pending_librarian_denied_count', $brf_pending_librarian_denied_count)
                ->with('brf_new_pending_lac_count', $brf_new_pending_lac_count)
                ->with('year_from', $year_from)
                ->with('users', $users)
                ->with('year_until', $year_untill);
    }

    /* Admin Page - Git Management (GET) */
    public function getAdminGitManagement()
    {
        return view('admin.git-management');
    }

    /* Admin Page - Git Pull (GET) */
    public function getAdminGitPull()
    {
        $git_pull = shell_exec("git pull 2>&1");
        return $git_pull;
    }

    /* Admin Page - Git Pull (POST) */
    public function postAdminGitPull()
    {
        $git_pull = shell_exec("git pull 2>&1");
        return $git_pull;
    }

    /* Admin Page - Email Management (GET) */
    public function getAdminEmailManagement()
    {
        return view('admin.email-management');
    }

    /* Admin Page - Email Test (POST) */
    public function postEmailTest()
    {
      // return Input::all();
      $emailAddress = Input::get('inputEmail');

      Mail::send('emails.test',
          [
              'brf_model_instance'        => $emailAddress
          ],
          function ($m) use ($emailAddress) {
          $m->from('librarian@iitm.ac.in', 'Library Portal Team');
          $m->to($emailAddress, "Test User")->subject('[Library] Test Email');
          // $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name);
          $m->subject('[Library] Test Email');
      });

      // Mail::send('emails.test', [], function ($m) {
      //       $m->from('msoffice@iitm.ac.in', 'DoMS, IIT Madras');
      //
      //     $m->to('yashmurty@gmail.com', 'Yash Murty')->subject('Your Reminder!');
      // });

      // check for failures
      if (Mail::failures()) {
          // return response showing failed emails
          return redirect('admin/email-management')
              ->with('globalalertmessage', 'Test Email Failed')
              ->with('globalalertclass', 'error');
      }
      // otherwise everything is okay ...
      return redirect('admin/email-management')
          ->with('globalalertmessage', 'Test Email Sent')
          ->with('globalalertclass', 'success');
    }

}
