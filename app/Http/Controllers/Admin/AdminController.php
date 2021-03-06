<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\policydetail;
use App\task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\UserCreationMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use DataTables;
use Gate;

class AdminController extends Controller
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
        return view('home');
    }
    
    
    public function usersList()
    {
        $users = view('admin.users');
        if (Gate::denies('manage-admin', $users)) {
        return redirect('/admin');
       
        }
        else{
        return $users;
        }
        
    }

    

    public function userForm($id='')
    {
       
        $data = [];

        if($id!=''){
            $data = DB::table('users')->select('*')->where('users.id',$id)->first();
            if(!empty($data)){
                $data->id = $id;
            }else{
                $userform= redirect('/admin/user-form');
            }

        }
        $userform= view('admin.userform',compact('data'));

        if (Gate::denies('manage-admin', $userform)) {
         return redirect('/admin');
        }
           else{
           return $userform;
       }

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
        $fetchData= response()->json($data, 200);

        if (Gate::denies('manage-admin', $fetchData)) {
            return redirect('/admin');
           }
              else{
              return $fetchData;
          }
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

        $data = new \stdClass();
        $data->name =  $request['first_name'].' '.$request['last_name'];
        $data->url =url('/login');
        $data->email =$request['email'];
        $data->password=$request['password'];
        $message="User Created Successfully";
        $data->message = $message;
        if($insertData){
            Mail::to($request['email'])->send(new UserCreationMail($data));
            return response()->json('Successfully created',200);
        }
        
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
      $data = DataTables::of(DB::table('users')->where('id','<>',auth::user()->id)->where('deleted_at','=',null)->orderby('id','desc'))->toJson();
      if($data)
      $fchdata= $data;
      
      if (Gate::denies('manage-admin', $fchdata)) {
        return redirect('/admin');
       }
          else{
          return $fchdata;
      }$data = [];
      $data = DataTables::of(DB::table('users')->where('id','<>',auth::user()->id)->where('deleted_at','=',null))->toJson();
      if($data)
      $fchdata= $data;
      
      if (Gate::denies('manage-admin', $fchdata)) {
        return redirect('/admin');
       }
       else{
          return $fchdata;
      }
    }

    public function deleteUser(Request $request){
        $data['deleted_at']=now();
       
        $json_data = user::whereId($request->userId)->update($data);
        return response()->json('Successfully Deleted', 200);
    }

    public function fetchNotification()
    {
       $data['policy'] = policydetail::select('c.id as cstId','c.first_name','c.last_name','inc.name as ctgName','prd.name as providerName',DB::raw('DATE_FORMAT(end_date, "%d %M") as end_date'))
        ->leftJoin('customers as c','customer_id','=','c.id')
        ->leftJoin('massparameter as inc','inc.id','=','policy_detail.insurance_ctg_id')
        ->leftJoin('massparameter as prd','prd.id','=','policy_detail.provider_id')
        ->whereRaw('CURDATE() >= DATE_SUB(end_date, INTERVAL 20 DAY) AND CURDATE()<end_date')
        ->get();
        $data['events'] = task::leftjoin('users as u','u.id','=','tasks.user_id')->leftjoin('users as au','au.id','=','tasks.assigned_id')
        ->select('tasks.*','au.first_name as a_first_name','au.last_name as a_last_name',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as taskdue_date'),DB::raw('DATE_FORMAT(tasks.created_at,"%d-%m-%Y") as assigned_on'),'u.first_name','u.last_name',DB::raw('tasks.id as taskid' ))
        ->where('tasks.type',NULL)->where('assigned_id','=',auth::user()->id)->where('tasks.status','<>','Completed')->where('tasks.due_date','=' ,today())->orderby('tasks.created_at','DESC')
        ->get();
        return response()->json($data, 200);
    }

    public function changePassword(Request $request){
      
        $validate = $this->validate(request(),[
                'oldPassword'=> 'required|min:6',
                'newPassword'  =>  'required|min:6',
                'confirmPassword'  =>  'same:newPassword',
        ]
        );

        if (Hash::check($request->oldPassword,Auth::user()->password)) {
            $data['password'] = hash::make($request->newPassword);
            try{
                user::whereId($request->id)->update($data);
                return response()->json("Password Changed ");
            }
            catch(\exception $e)
            {
                return response()->json("Password Not Changed ");
               
            }
        }
        else{
           
          return response()->json(['errors'=>['password'=>[0=>'Wrong old password']]],400);
           
        }
   
    }
    
}



