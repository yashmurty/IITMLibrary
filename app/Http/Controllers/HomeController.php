<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Validator;
use Auth;
use View;
use DB;

use App\BasicRequisitionForm;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoot()
    {
        return view('pages.welcome');
    }

    public function getHome()
    {
        return view('pages.home');
    }

    // Book Requisition Form
    public function getBookRequisitionForm()
    {
        return view('pages.BookRequisitionForm');
    }

    public function postBookRequisitionForm()
    {

        $validator = Validator::make(Input::all(), [
            'inputAuthor' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/bookrequisitionform')
                ->withInput()
                ->withErrors($validator);
        }

        $basicRequisitionFormData = BasicRequisitionForm::create(array(
            'doctype'           => Input::get('optionsDocumentType'),
            'author'            => Input::get('inputAuthor'),
            'title'             => Input::get('inputTitle'),
            'publisher'         => Input::get('inputPublisher'),
            'agency'            => Input::get('inputAgency'),
            'isbn'              => Input::get('inputISBN'),
            'volumne'           => Input::get('inputVolume'),
            'price'             => Input::get('inputPrice'),
            'sectioncatalogue'  => Input::get('inputSectionCatalogue'),
            'numberofcopies'    => Input::get('inputNumberOfCopies'),
            'laravel_user_id'   => Auth::user()->id,
            'iitm_dept_code'    => Auth::user()->iitm_dept_code
        ));

        //return Auth::user();

        return redirect('home')
                ->with('globalalertmessage', 'Book Request Submitted')
                ->with('globalalertclass', 'success');
    }

    // Request Status
    public function getRequestStatus()
    {
        $laravel_user_id = Auth::user()->id;

        $user_brfs = DB::table('brfs')
                        ->where('laravel_user_id', $laravel_user_id)
                        ->orderBy('id', 'desc')
                        ->get();
        if(!empty($user_brfs)){
    
            return view('pages.requeststatus')
                    ->with('user_brfs', $user_brfs);

        } else {
            // return "No Requests Found";
            return view('pages.requeststatus')
                    ->with('user_brfs', null);
        }

    }
}
