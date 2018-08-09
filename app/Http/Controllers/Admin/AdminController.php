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
    
   
    public function usersList()
    {
      return view('admin.users');
    }

    

    public function userForm($id='')
    {
        $data = [];
        if($id!=''){
            $data = DB::table('users')->select('*')->where('users.id',$id)->first();
            if(!empty($data)){
                $data->id = $id;
            }else{
                return redirect('/admin/user-form');
            }

        }
        return view('admin.userform',compact('data'));
    }

    public function fetchUser(Request $request)
    { 
        $data = [];
        if($request->id!=''){
            $data = DB::table('users')->select('*')->where('users.id',$request->id)->first();
            if(!empty($data)){
                $data->id = $request->id;
            }
        }
        return response()->json($data, 200);
    }

    public function saveUser(Request $request)
    {
        
         $validate = $this->validate(request(),[
            'first_name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            ]
        );
        
        if($request->hasFile('photo')){
            $file=$request->file('photo');
            $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/userphoto');
            $file->move($destinationPath, $imageName);
            $userphoto['photo'] = $imageName;
        }
        else{
            $userphoto['photo'] ='userdefault.jpg';
        }
  
        $insertData = user::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'photo' => $userphoto['photo'],
            'password' => Hash::make($request['password']),
            'role' => $request['role'],
        ]);
        if($insertData)
        return response()->json('Successfully created',200);
            return redirect()->back()->withErrors($validate->errors());
       
    }

    public function updateUser(Request $request,user $user){
        $user = DB::table('users');
        $checkAdmin =  $user->select('users.email')->where('users.email',$request->email)->where('users.id','<>',$request->id)->first();
        if(empty($checkAdmin)){
            if($request->hasFile('photo')){
                $file=$request->file('photo');
                $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/userphoto');
                $file->move($destinationPath, $imageName);
                $userphoto['photo'] = $imageName;
            }
            else{
                $userphoto['photo'] =$request->photo;
            }
            $data['photo'] = $userphoto['photo'];
            $data['first_name'] = $request->first_name;
            $data['last_name'] = $request->last_name;
            $data['email'] = $request->email;
            $data['phone'] = $request->phone;
            $data['role'] = $request->role;
            if(user::whereId($request->id)->update($data));
            return response()->json('Successfully updated',200);
        }
        else{
            return response()->json('Email Aready Taken', 500);
        }
      
    }

    public function fetchAllUsers(){
      $data = [];
      $data = DataTables::of(DB::table('users')->where('id','<>',auth::user()->id))->toJson();
      if($data)
      return $data;
    }

    public function deleteUser(Request $request){
        $json_data = user::whereId($request->id)->delete();
        return response()->json('Successfully Deleted', 200);
    }

    
}



