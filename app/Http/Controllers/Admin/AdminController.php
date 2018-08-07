<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Admin;
use DataTables;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function dashboard()
    {
      $usrType = Auth::guard('admin')->user()->usertype; 
      return view('backend.dashboard',compact('usrType'));
    }

    public function usersList()
    {
      return view('backend.users');
    }

    

    public function userForm($id='')
    {
        $data = [];
        if($id!=''){
            $data = DB::table('admins')->select('*')->where('admins.id',$id)->first();
            if(!empty($data)){
                $data->id = $id;
            }else{
                return redirect('/admin/user-form');
            }

        }
        return view('backend.userform',compact('data'));
    }

    public function fetchUser(Request $request)
    { 
        $data = [];
        if($request->id!=''){
            $data = DB::table('admins')->select('*')->where('admins.id',$request->id)->first();
            if(!empty($data)){
                $data->id = $request->id;
            }
        }
        return response()->json($data, 200);
    }

    public function saveUser(Request $request)
    {
         $request = $this->validate(request(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:3',
            'usertype' => 'required',
            ]
        );
        $insertData = Admin::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'usertype' => $request['usertype'],
        ]);
        if($insertData)
        return response()->json('Successfully created',200);
            return redirect()->back()->withErrors($request->errors());
       
    }

    public function updateUser(Request $request,Admin $admin){
        $admin = DB::table('admins');
        $checkAdmin =  $admin->select('admins.email')->where('admins.email',$request->email)->where('admins.id','<>',$request->id)->first();
        if(empty($checkAdmin)){
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['usertype'] = $request->usertype;
            if(Admin::whereId($request->id)->update($data));
            return response()->json('Successfully updated',200);
        }
      
    }

    public function fetchAllUsers(){
      $data = [];
      $data = DataTables::of(DB::table('admins'))->toJson();
      if($data)
      return $data;
    }

    public function deleteUser(Request $request){
        $json_data = Admin::whereId($request->id)->delete();
        return response()->json('Successfully Deleted', 200);
    }
}

