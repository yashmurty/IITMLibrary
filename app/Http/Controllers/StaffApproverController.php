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
use App\BookBudget;
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

    private $INR_Regex = "/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i";

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

    // Request Status - View BRF
    public function getStaffAdminRequestStatusViewBRF($brf_id)
    {
        $admin_user_brf = DB::table('brfs')
            ->where('lac_status', "approved")
            ->where('id', $brf_id)
            ->orderBy('id', 'desc')
            ->first();

        // Initialize email preview variable
        $email_preview = '';
        $librarian_approver_name = '';
        $downloader_approver_name = '';
        $purchase_price_approver_name = '';
        $department_year_from_until = []; // Initialize new variable

        if (!empty($admin_user_brf)) {
            // Get the full BRF model instance for relationships
            $brf_model_instance = BasicRequisitionForm::find($brf_id);

            // Get the user associated with the BRF
            $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

            // Get the department code from the BRF
            $iitm_dept_code = $brf_model_instance->iitm_dept_code;

            // Get all budget years for this department
            $department_year_from_until = BookBudget::where('iitm_dept_code', $iitm_dept_code)
                ->orderBy('year_from_until', 'desc')
                ->pluck('year_from_until')
                ->toArray();

            // Check if librarian_approver_iitm_id exists and is not empty
            if (!empty($admin_user_brf->librarian_approver_iitm_id)) {
                // Find the librarian user based on iitm_id
                $librarian_user = User::where('iitm_id', $admin_user_brf->librarian_approver_iitm_id)->first();

                // If found, get the name
                if ($librarian_user) {
                    $librarian_approver_name = $librarian_user->name;
                }
            }

            // Check if downloader_approver_iitm_id exists and is not empty
            if (!empty($admin_user_brf->downloader_approver_iitm_id)) {
                // Find the downloader user based on iitm_id
                $downloader_user = User::where('iitm_id', $admin_user_brf->downloader_approver_iitm_id)->first();

                // If found, get the name
                if ($downloader_user) {
                    $downloader_approver_name = $downloader_user->name;
                }
            }

            // Check if purchase_price_approver_iitm_id exists and is not empty
            if (!empty($admin_user_brf->purchase_price_approver_iitm_id)) {
                // Find the purchase price approver user based on iitm_id
                $purchase_price_approver_user = User::where('iitm_id', $admin_user_brf->purchase_price_approver_iitm_id)->first();

                // If found, get the name
                if ($purchase_price_approver_user) {
                    $purchase_price_approver_name = $purchase_price_approver_user->name;
                }

                // Format the purchase price to INR
                $admin_user_brf->purchase_price_of_book = preg_replace($this->INR_Regex, "$1,", $admin_user_brf->purchase_price_of_book);
            }

            // Render the email template to create the preview
            $email_preview = view('emails.brfUpdatedByLibrarian', [
                'brf_model_instance' => $brf_model_instance,
                'brf_model_user_instance' => $brf_model_user_instance
            ])->render();

            return view('staff-approver.requeststatus-view-brf')
                ->with('admin_user_brf', $admin_user_brf)
                ->with('email_preview', $email_preview)
                ->with('librarian_approver_name', $librarian_approver_name)
                ->with('downloader_approver_name', $downloader_approver_name)
                ->with('purchase_price_approver_name', $purchase_price_approver_name)
                ->with('department_year_from_until', $department_year_from_until);
        } else {
            return view('staff-approver.requeststatus-view-brf')
                ->with('admin_user_brf', null)
                ->with('email_preview', '')
                ->with('librarian_approver_name', '')
                ->with('downloader_approver_name', '')
                ->with('purchase_price_approver_name', '')
                ->with('department_year_from_until', []);
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
        // Set lac_status_date to the current date and time
        $brf_model_instance->librarian_status_date = Carbon::now();
        $brf_model_instance->librarian_approver_iitm_id = Auth::user()->iitm_id;
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

    // Edit BRF - Final Procurment Finished Step (Book Details)
    public function putStaffAdminRequestStatusEditBRFFinalStep($brf_id)
    {
        $brf_model_instance = BasicRequisitionForm::find($brf_id);

        // Check if purchase price is being modified
        $new_purchase_price = Input::get('edit-purchase-price');
        if ($brf_model_instance->purchase_price_of_book != $new_purchase_price) {
            // If price is modified, set the approver ID
            $brf_model_instance->purchase_price_approver_iitm_id = Auth::user()->iitm_id;
        }

        // Update the book details fields
        $brf_model_instance->account_number_of_book = Input::get('edit-account-number');
        $brf_model_instance->call_number_of_book = Input::get('edit-call-number');
        $brf_model_instance->purchase_price_of_book = $new_purchase_price;
        $brf_model_instance->optional_ebook_hyperlink = Input::get('edit-ebook-link');
        $brf_model_instance->purchase_target_year_from_until = Input::get('edit-purchase-target-year');

        $brf_model_instance->save();

        return redirect('staff-approver/requeststatus/brf/' . $brf_id)
            ->with('globalalertmessage', 'Book details successfully updated.')
            ->with('globalalertclass', 'success');
    }

    /**
     * Send an update email to the user for a specific BRF
     *
     * @param  int  $brf_id
     * @return \Illuminate\Http\Response
     */
    public function postStaffAdminBRFSendEmail($brf_id)
    {
        try {
            // Find the BRF
            $brf_model_instance = BasicRequisitionForm::find($brf_id);

            if (!$brf_model_instance) {
                return redirect()
                    ->back()
                    ->with('globalalertmessage', 'BRF not found')
                    ->with('globalalertclass', 'error');
            }

            // Get the user associated with the BRF
            $brf_model_user_instance = User::find($brf_model_instance->laravel_user_id);

            if (!$brf_model_user_instance) {
                return redirect()
                    ->back()
                    ->with('globalalertmessage', 'User associated with BRF not found')
                    ->with('globalalertclass', 'error');
            }

            // Send email notification
            Mail::send(
                'emails.brfUpdatedByLibrarian',
                [
                    'brf_model_instance'        => $brf_model_instance,
                    'brf_model_user_instance'   => $brf_model_user_instance
                ],
                function ($m) use ($brf_model_instance, $brf_model_user_instance) {
                    $m->from('librarian@iitm.ac.in', 'Library Portal Team');
                    $m->to($brf_model_user_instance->email, $brf_model_user_instance->name)
                        ->subject('[Library] Update on your Book Request');
                }
            );

            return redirect()
                ->back()
                ->with('globalalertmessage', 'Update email sent successfully')
                ->with('globalalertclass', 'success');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('globalalertmessage', 'Error sending email: ' . $e->getMessage())
                ->with('globalalertclass', 'error');
        }
    }
}
