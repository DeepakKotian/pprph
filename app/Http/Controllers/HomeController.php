<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\customer;
use Auth;

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
        $userCount = user::where('deleted_at','=',null)->where('id','<>' ,Auth::user()->id)->count();
        $customerCount = customer::where('is_family','=','0');
        if(Auth::user()->role !== 1)
        {
            $customerCount->where('user_id',Auth::user()->id);
        } 
        $customerCount = $customerCount->count();
        return view('admin.dashboard',compact('userCount','customerCount'));
    }
}
