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
use App\BookBudget;
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

    // Request Status
    public function getAdminRequestStatus()
    {
        // Redirect to staff approver request status
        return redirect()->route('staff-approver-requeststatus');
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

        if (!empty($admin_user_brfs)) {

            foreach ($admin_user_brfs as $key => $admin_user_brf) {

                $brf_model_instance = BasicRequisitionForm::find($admin_user_brf->id);
                $brf_model_instance->download_status = "downloaded";
                $brf_model_instance->remarks = "The procurement process has been initiated.";
                $brf_model_instance->save();

                $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

                Mail::send(
                    'emails.acceptedbylibrarian',
                    [
                        'brf_model_instance'        => $brf_model_instance,
                        'brf_model_user_instance'   => $brf_model_user_instance
                    ],
                    function ($m) use ($brf_model_instance, $brf_model_user_instance) {
                        $m->from('librarian@iitm.ac.in', 'Library Portal Team');
                        $m->to($brf_model_user_instance->email, $brf_model_user_instance->name)->subject('[Library] Request Approved for Book');
                        // $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name)->subject('[Library] Request Approved for Book');
                        // No commented test for CC here.

                    }
                );

                $brf_array_row = (array) $admin_user_brf;
                $brf_array[] = $brf_array_row;
            }
            $data = (array) $brf_array;
            $date = Carbon::now();
            $currentDateTime = $date->toDateTimeString();

            Excel::create($currentDateTime, function ($excel) use ($data) {

                $excel->sheet('Sheetname', function ($sheet) use ($data) {

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

        if (!empty($admin_user_brfs)) {

            foreach ($admin_user_brfs as $key => $admin_user_brf) {

                $brf_array_row = (array) $admin_user_brf;
                $brf_array[] = $brf_array_row;
            }
            $data = (array) $brf_array;
            $date = Carbon::now();
            $currentDateTime = $date->toDateTimeString();

            Excel::create($currentDateTime, function ($excel) use ($data) {

                $excel->sheet('Sheetname', function ($sheet) use ($data) {

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

    public function getAdminRequestStatusExportExcelStatusYear($archived_status, $year_from_until)
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
                    ->whereDate('created_at', '>=', $years[0] . '-04-01')
                    ->whereDate('created_at', '<=', $years[1] . '-03-31')
                    ->orderBy('id', 'desc')
                    ->get();
                break;

            case 'denied':
                $admin_user_brfs = DB::table('brfs')
                    ->where('lac_status', "approved")
                    ->where('librarian_status', "denied")
                    ->whereDate('created_at', '>=', $years[0] . '-04-01')
                    ->whereDate('created_at', '<=', $years[1] . '-03-31')
                    ->orderBy('id', 'desc')
                    ->get();
                break;
        }

        // dd($admin_user_brfs);

        if (!empty($admin_user_brfs)) {

            foreach ($admin_user_brfs as $key => $admin_user_brf) {

                $brf_array_row = (array) $admin_user_brf;
                $brf_array[] = $brf_array_row;
            }
            $data = (array) $brf_array;
            $date = Carbon::now();
            $currentDateTime = $date->toDateTimeString();

            Excel::create($currentDateTime, function ($excel) use ($data) {

                $excel->sheet('Sheetname', function ($sheet) use ($data) {

                    $sheet->fromArray($data);
                });
            })->export('xls');

            return $archived_status . " Status Excel Exported";
        } else {
            return redirect('admin/requeststatus/archived')
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
        if (!empty($admin_user_brfs)) {

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
                    ->whereDate('created_at', '>=', $years[0] . '-04-01')
                    ->whereDate('created_at', '<=', $years[1] . '-03-31')
                    ->orderBy('id', 'desc')
                    ->get();
                break;

            case 'denied':
                $admin_user_brfs = DB::table('brfs')
                    ->where('lac_status', "approved")
                    ->where('librarian_status', "denied")
                    ->whereDate('created_at', '>=', $years[0] . '-04-01')
                    ->whereDate('created_at', '<=', $years[1] . '-03-31')
                    ->orderBy('id', 'desc')
                    ->get();
                break;
        }

        if (!empty($admin_user_brfs)) {

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
        if (!empty($lac_users)) {

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
        if (!empty($lac_user)) {

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
                ]
            );
        return redirect('/admin/lacmembers');
    }

    public function getAdminStaffMembers()
    {
        $admin_users = DB::table('admin_users')
            ->select(
                'admin_users.id',
                'admin_users.iitm_id',
                'admin_users.role',
                'users.name',
                'users.email'
            )
            ->leftJoin('users', 'admin_users.iitm_id', '=', 'users.iitm_id')
            ->orderBy('admin_users.id', 'asc')
            ->get();

        // Transform the results to handle nulls from the join
        // For older Laravel versions that return arrays
        foreach ($admin_users as $key => $user) {
            // If name or email is null (user not found in users table), 
            // set them to null explicitly for consistency
            if (!isset($user->name)) {
                $admin_users[$key]->name = null;
            }
            if (!isset($user->email)) {
                $admin_users[$key]->email = null;
            }
        }

        if (!empty($admin_users)) {
            return view('admin.admin-staff-members')
                ->with('admin_users', $admin_users);
        } else {
            return view('admin.admin-staff-members')
                ->with('admin_users', null);
        }
    }

    /**
     * Add a new admin user.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdminStaffAddUser()
    {
        // Get input data
        $iitm_id = Input::get('iitm_id');
        $role = Input::get('role');

        // Validate input
        if (empty($iitm_id)) {
            return redirect()->back()->with('error', 'IITM ID is required.')->withInput();
        }

        // Validate role is one of the configured role values
        if (!in_array($role, array_values(config('roles')))) {
            return redirect()->back()->with('error', 'Invalid role specified.')->withInput();
        }

        // Check if user with this IITM ID already exists in admin_users
        $existingUser = DB::table('admin_users')
            ->where('iitm_id', $iitm_id)
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'User with this IITM ID already exists in admin users.')->withInput();
        }

        // Get user details from users table
        $user = DB::table('users')->where('iitm_id', $iitm_id)->first();

        // Prepare data for insertion
        $userData = [
            'iitm_id' => $iitm_id,
            'role' => $role,
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()')
        ];

        // Add name and email if user found in users table
        if ($user) {
            $userData['name'] = $user->name;
            $userData['email'] = $user->email;
        } else {
            $userData['name'] = "Not Found";
            $userData['email'] = "Not Found";
        }

        // Insert new admin user
        $inserted = DB::table('admin_users')->insert($userData);

        if ($inserted) {
            return redirect('/admin/staffmembers')->with('success', 'New user added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add new user.')->withInput();
        }
    }

    /**
     * Update the IITM ID and role of an admin user.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function postAdminStaffEditRole($user_id)
    {
        // Get input data
        $iitm_id = Input::get('iitm_id');
        $role = Input::get('role');

        // Validate input
        if (empty($iitm_id)) {
            return redirect()->back()->with('error', 'IITM ID is required.');
        }

        // Validate role is one of the configured role values
        if (!in_array($role, array_values(config('roles')))) {
            return redirect()->back()->with('error', 'Invalid role specified.');
        }

        // Get user details from users table
        $user = DB::table('users')->where('iitm_id', $iitm_id)->first();

        // Prepare update data
        $updateData = [
            'iitm_id' => $iitm_id,
            'role' => $role
        ];

        // If user is found, include name and email from users table
        if ($user) {
            $updateData['name'] = $user->name;
            $updateData['email'] = $user->email;
        } else {
            // If user not found, set name and email to NULL
            $updateData['name'] = null;
            $updateData['email'] = null;
        }

        // Update the user's information and role
        $updated = DB::table('admin_users')
            ->where('id', $user_id)
            ->update($updateData);

        if ($updated) {
            return redirect('/admin/staffmembers')->with('success', 'User information updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update user information.');
        }
    }

    /**
     * Delete an admin user.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function deleteAdminStaffUser($user_id)
    {
        // Delete the admin user
        $deleted = DB::table('admin_users')
            ->where('id', $user_id)
            ->delete();

        if ($deleted) {
            return redirect('/admin/staffmembers')->with('success', 'Admin user removed successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to remove admin user.');
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

        $brf_all_count = BasicRequisitionForm::whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that are pending but have been approved by LAC Members
        $brf_pending_lac_approved_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('librarian_status', NULL)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that are pending but have been approved by LAC Members and Librarian
        $brf_pending_librarian_approved_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('librarian_status', "approved")
            ->where('download_status', NULL)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that have been Successfully downloaded and approved
        $brf_approved_downloaded_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('librarian_status', "approved")
            ->where('download_status', "downloaded")
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // Requests that have been Denied by LAC Members
        $brf_pending_lac_denied_count = BasicRequisitionForm::where('lac_status', "denied")
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that have been Denied by Librarian
        $brf_pending_librarian_denied_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('librarian_status', "denied")
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // Requests that are new and pending LAC Member Approval
        $brf_new_pending_lac_count = BasicRequisitionForm::where('lac_status', NULL)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // User Analytics
        $users = DB::table('users')
            ->get();
        foreach ($users as $key => $user) {
            $brf_requests_count = BasicRequisitionForm::where('iitm_id', $user->iitm_id)
                ->whereDate('created_at', '>=', $years[0] . '-04-01')
                ->whereDate('created_at', '<=', $years[1] . '-03-31')
                ->count();
            $user->brf_requests_count = $brf_requests_count;
        }
        usort($users, function ($a, $b) { //Sort the array using a user defined function
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

        $brf_all_count = BasicRequisitionForm::where('iitm_dept_code', $iitm_dept_code)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // Requests that are pending but have been approved by LAC Members
        $brf_pending_lac_approved_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('iitm_dept_code', $iitm_dept_code)
            ->where('librarian_status', NULL)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that are pending but have been approved by LAC Members and Librarian
        $brf_pending_librarian_approved_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('iitm_dept_code', $iitm_dept_code)
            ->where('librarian_status', "approved")
            ->where('download_status', NULL)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that have been Successfully downloaded and approved
        $brf_approved_downloaded_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('iitm_dept_code', $iitm_dept_code)
            ->where('librarian_status', "approved")
            ->where('download_status', "downloaded")
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // Requests that have been Denied by LAC Members
        $brf_pending_lac_denied_count = BasicRequisitionForm::where('lac_status', "denied")
            ->where('iitm_dept_code', $iitm_dept_code)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();
        // Requests that have been Denied by Librarian
        $brf_pending_librarian_denied_count = BasicRequisitionForm::where('lac_status', "approved")
            ->where('iitm_dept_code', $iitm_dept_code)
            ->where('librarian_status', "denied")
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // Requests that are new and pending LAC Member Approval
        $brf_new_pending_lac_count = BasicRequisitionForm::where('lac_status', NULL)
            ->where('iitm_dept_code', $iitm_dept_code)
            ->whereDate('created_at', '>=', $years[0] . '-04-01')
            ->whereDate('created_at', '<=', $years[1] . '-03-31')
            ->count();

        // User Analytics - Department-wise
        $users = DB::table('users')
            ->where('iitm_dept_code', $iitm_dept_code)
            ->get();
        foreach ($users as $key => $user) {
            $brf_requests_count = BasicRequisitionForm::where('iitm_id', $user->iitm_id)
                ->whereDate('created_at', '>=', $years[0] . '-04-01')
                ->whereDate('created_at', '<=', $years[1] . '-03-31')
                ->count();
            $user->brf_requests_count = $brf_requests_count;
        }
        usort($users, function ($a, $b) { //Sort the array using a user defined function
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

    /* Admin Page - Book Budget Year List (GET) */
    public function getBookBudgetYearList()
    {
        return view('admin.book-budget-year-list');
    }

    private $INR_Regex = "/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i";

    /* Admin Page - Book Budget Departments (GET) */
    public function getBookBudgetDepartments($iitm_dept_code = "ALL", $year_from_until = "2023-2024")
    {
        if ($iitm_dept_code == "ALL") {
            $lac_users_departments = DB::table('lac_users')
                ->orderBy('iitm_dept_code', 'asc')
                ->get();
            $book_budgets = DB::table('book_budgets')
                ->where('year_from_until', $year_from_until)
                ->get();

            $lac_users_departments = collect($lac_users_departments);
            $book_budgets = collect($book_budgets);

            $default_budget = [
                "year_from_until" => $year_from_until,
                "budget_allocated" => "0",
                "budget_spent" => "0",
                "budget_on_order" => "0",
                "budget_available" => "0"
            ];

            $lac_users_departments_with_budget = $lac_users_departments->map(function ($department) use ($book_budgets, $default_budget) {
                $budget = $book_budgets->where('iitm_dept_code', $department->iitm_dept_code)->first();
                $merged = (object) array_merge(
                    (array) $department,
                    $budget ? (array) $budget : $default_budget
                );

                // Format budget fields using regex
                $merged->budget_allocated = preg_replace($this->INR_Regex, "$1,", $merged->budget_allocated);
                $merged->budget_spent = preg_replace($this->INR_Regex, "$1,", $merged->budget_spent);
                $merged->budget_on_order = preg_replace($this->INR_Regex, "$1,", $merged->budget_on_order);
                $merged->budget_available = preg_replace($this->INR_Regex, "$1,", $merged->budget_available);

                return $merged;
            });
        } else {
            $lac_users_departments_with_budget = DB::table('book_budgets')
                ->where('iitm_dept_code', $iitm_dept_code)
                ->orderBy('year_from_until', 'desc')
                ->get();

            $lac_users_departments_with_budget = collect($lac_users_departments_with_budget)
                ->map(function ($budget) {
                    // Format budget fields using regex
                    $budget->budget_allocated = preg_replace($this->INR_Regex, "$1,", $budget->budget_allocated);
                    $budget->budget_spent = preg_replace($this->INR_Regex, "$1,", $budget->budget_spent);
                    $budget->budget_on_order = preg_replace($this->INR_Regex, "$1,", $budget->budget_on_order);
                    $budget->budget_available = preg_replace($this->INR_Regex, "$1,", $budget->budget_available);
                    return $budget;
                });
        }

        return view('admin.book-budget-departments')
            ->with('lac_users_departments_with_budget', $lac_users_departments_with_budget)
            ->with('iitm_dept_code', $iitm_dept_code)
            ->with('year_from_until', $year_from_until);
    }

    /* Admin Book Budget - Department and Year UPSERT (POST) */
    public function postBookBudgetDepartments()
    {
        $input = Input::all();

        try {
            $bookBudget = BookBudget::firstOrNew([
                'iitm_dept_code' => $input['edit-department'],
                'year_from_until' => $input['edit-year']
            ]);

            $bookBudget->budget_allocated = $input['edit-budget-allocated'];
            $bookBudget->budget_spent = $input['edit-budget-spent'];
            $bookBudget->budget_on_order = $input['edit-budget-on-order'];
            $bookBudget->budget_available = $input['edit-budget-available'];

            $bookBudget->save();

            return redirect()->back()->with('success', 'Book budget updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating book budget: ' . $e->getMessage());
        }
    }

    /* Admin Page - Git Management (GET) */
    public function getAdminGitManagement()
    {
        return view('admin.git-management');
    }

    /* Admin Page - Git Management - Git Pull (GET) */
    public function getAdminGitManagementGitPull()
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

        Mail::send(
            'emails.test',
            [
                'brf_model_instance'        => $emailAddress
            ],
            function ($m) use ($emailAddress) {
                $m->from('librarian@iitm.ac.in', 'Library Portal Team');
                $m->to($emailAddress, "Test User")->subject('[Library] Test Email');
                // $m->to("ae11b049@smail.iitm.ac.in", $brf_model_user_instance->name);
                $m->subject('[Library] Test Email');
            }
        );

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
