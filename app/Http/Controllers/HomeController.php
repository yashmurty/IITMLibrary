<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Validator;

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

    public function getBookRequisitionForm()
    {
        return view('pages.BookRequisitionForm');
    }

    public function postBookRequisitionForm()
    {

        $validator = Validator::make(Input::all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect('/bookrequisitionform')
                ->withInput()
                ->withErrors($validator);
        }



        return Input::all();
    }
}
