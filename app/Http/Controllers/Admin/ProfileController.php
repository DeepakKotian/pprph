<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;

class ProfileController extends Controller
{
    //
    
    public function index(){
        return view('admin.profile');
       }
    public function userData(){
        $userid=Auth::user()->id;
        $profileData=user::find($userid);
        return response()->json($profileData, 200);

    }

    public function updateProfile(Request $request){
        $userid=Auth::user()->id;
        $user = DB::table('users');
        $email =  $user->select('users.email')->where('users.email',$request->email)->where('users.id','<>',$userid)->first();
      
        if(empty($email)){

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
            if(user::whereId($userid)->update($data));
            return response()->json('Successfully updated',200);
        }
        else{
            return response()->json('Email Aready Taken', 500);
        }
        

    }

    

}
