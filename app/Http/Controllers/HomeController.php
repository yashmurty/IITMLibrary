<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Validator;
use Auth;
use View;
use DB;
use Mail;
use GuzzleHttp\Client;

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
            'iitm_id'           => Auth::user()->iitm_id,
            'faculty'           => Auth::user()->name,
            'iitm_dept_code'    => Auth::user()->iitm_dept_code
        ));

        //return Auth::user();

        $lac_user_instance = DB::table('lac_users')
                                    ->where('iitm_dept_code', '=', Auth::user()->iitm_dept_code)
                                    ->first();

        // return $lac_user_instance->name;
        $inputTitle =  Input::get('inputTitle');

        Mail::send('emails.newbrf', 
                [
                    'lac_user_instance'     => $lac_user_instance,
                    'inputTitle'            => $inputTitle 
                ], 
                function ($m) use ($lac_user_instance, $inputTitle) {
                $m->from('no-reply@iitm.ac.in', 'Library Portal Team');
                $m->to($lac_user_instance->lac_email_id, $lac_user_instance->name)->subject('[Library] New Request for Book');
                // $m->to("ae11b049@smail.iitm.ac.in", $lac_user_instance->name)->subject('[Library] New Request for Book');
            });        

        return redirect('home')
                ->with('globalalertmessage', 'Book Request Submitted')
                ->with('globalalertclass', 'success');
    }

    // Request Status
    public function getRequestStatus()
    {
        $laravel_user_id = Auth::user()->id;

        // Mail::send('emails.test', ['laravel_user_id' => $laravel_user_id], function ($m) use ($laravel_user_id) {
        //     $m->from('hello@smail.iitm.ac.in', 'Your Application');

        //     $m->to("ae11b049@smail.iitm.ac.in", "Yash")->subject('Your Reminder!');
        // });

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

    public function postBookRequisitionFormISBN()
    {

        $validator = Validator::make(Input::all(), [
            'inputISBN' => 'min:10',
        ]);

        if ($validator->fails()) {
            return redirect('/home')
                ->withInput()
                ->withErrors($validator)
                ->with('globalalertmessage', 'ISBN number should be 10 or 13 Digits only.')
                ->with('globalalertclass', 'error');
        }
        // return Input::all();
        $inputISBN = Input::get('inputISBN');
        $inputISBN = str_replace(' ', '', $inputISBN);

        $client = new Client();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q=isbn:'.$inputISBN);

        $body = $response->getBody();
        $bodyContents = $body->getContents();
        $bodyContentsJSON = json_decode($bodyContents);
        $bodyContentsJSONtotalItems = $bodyContentsJSON->totalItems;

        if (!($bodyContentsJSONtotalItems == 0)) {

            $bodyContentsJSONItems = $bodyContentsJSON->items;
            // dd($bodyContentsJSONItems);

            if (isset($bodyContentsJSONItems[0]->volumeInfo->title)) {
                $title = $bodyContentsJSONItems[0]->volumeInfo->title;
            } else {
                $title = null;
            }
            if (isset($bodyContentsJSONItems[0]->volumeInfo->authors)) {
                $authors = implode(", ", $bodyContentsJSONItems[0]->volumeInfo->authors);
            } else {
                $authors = null;
            }
            if (isset($bodyContentsJSONItems[0]->volumeInfo->publisher)) {
                $publisher = $bodyContentsJSONItems[0]->volumeInfo->publisher;
            } else {
                $publisher = null;
            }
            if (isset($bodyContentsJSONItems[0]->volumeInfo->publishedDate)) {
                $publishedDate = $bodyContentsJSONItems[0]->volumeInfo->publishedDate;
            } else {
                $publishedDate = null;
            }
            if (isset($bodyContentsJSONItems[0]->volumeInfo->imageLinks->thumbnail)) {
                $thumbnail = $bodyContentsJSONItems[0]->volumeInfo->imageLinks->thumbnail;
            } else {
                $thumbnail = null;
            }
            if (isset($bodyContentsJSONItems[0]->accessInfo->webReaderLink)) {
                $webReaderLink = $bodyContentsJSONItems[0]->accessInfo->webReaderLink;
            } else {
                $webReaderLink = "#";
            }


            return view('pages.BookRequisitionFormISBN')
                    ->with('authors', $authors)
                    ->with('title', $title)
                    ->with('publisher', $publisher)
                    ->with('inputISBN', $inputISBN)
                    ->with('publishedDate', $publishedDate)
                    ->with('thumbnail', $thumbnail)
                    ->with('webReaderLink', $webReaderLink);


        } else {
            return redirect('/home')
                ->with('globalalertmessage', 'Book not found with this ISBN')
                ->with('globalalertclass', 'error');
        }




    }
}
