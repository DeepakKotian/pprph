<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\task;
use DB;
use Auth;
use App\User;
use DataTables;

class taskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  
        return view('admin.tasks');
    }

    public function fetchTaskList()
    {
        $taskList = [];
        $taskList = DataTables::of(task::leftjoin('users as u','u.id','=','tasks.user_id')->select('tasks.*',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as due_date'),'u.id','u.first_name','u.last_name'))->toJson();
        if($taskList)
          return $taskList;
    
    }
    public function fetchMyTaskList()
    {
        $taskList = [];
        $taskList = DataTables::of(task::leftjoin('users as u','u.id','=','tasks.user_id')->select('tasks.*',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as due_date'),'u.id','u.first_name','u.last_name')->where('assigned_id','=',Auth::user()->id))->toJson();
        if($taskList)
          return $taskList;
    
    }
    public function fetchTaskUsers()
    {
        $userList = User::where('role','<>',1)->get();
        if($userList)
        return $userList;
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
   
        $validate = $this->validate(request(),[
            'task_name' => 'required',
            'due_date' => 'required',
            'assigned_id' => 'required',
            ]
        );

        $insertData = task::create([
            'task_name' => $request['task_name'],
            'task_detail' => $request['task_detail'],
            'status' => 'Pending',
            'user_id'=>Auth::user()->id,
            'due_date'=> date('Y-m-d',strtotime($request['due_date'])),
            'assigned_id' => $request['assigned_id'],
        ]);
        if($insertData)
        return response()->json('Successfully created',200);
        return redirect()->back()->withErrors($validate->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy(cr $cr)
    {
        //
    }
}
