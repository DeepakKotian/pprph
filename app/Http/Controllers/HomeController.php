<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\customer;

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
    public function index()
    {
        $userCount=user::count();
        $customerCount=customer::where('is_family','=','0')->count();
        return view('admin.dashboard',compact('userCount','customerCount'));
    }
}
