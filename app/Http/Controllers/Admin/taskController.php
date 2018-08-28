<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\task;
use App\taskhistory;
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
        $taskList = DataTables::of(task::leftjoin('users as u','u.id','=','tasks.user_id')->select('tasks.*',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as due_date'),DB::raw('DATE_FORMAT(tasks.created_at,"%d-%m-%Y") as assigned_on'),DB::raw('tasks.id as taskid' ),'u.id','u.first_name','u.last_name'))->toJson();
        if($taskList)
          return $taskList;
    
    }
    
    public function mytask()
    {
      
        return view('admin.mytasks');
      
    }
    
    public function fetchMyTaskList()
    {
        $mytaskList = [];
        $mytaskList = DataTables::of(task::leftjoin('users as u','u.id','=','tasks.user_id')->select('tasks.*',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as due_date'),DB::raw('DATE_FORMAT(tasks.created_at,"%d-%m-%Y") as assigned_on'),'u.id','u.first_name','u.last_name',DB::raw('tasks.id as taskid' ))->where('assigned_id','=',Auth::user()->id))->toJson();
        if($mytaskList)
          return $mytaskList;
    
    }

    public function fetchTaskUsers()
    {
        $userList = User::where('role','<>',1)->where('deleted_at','=',null)->get();
        if($userList)
        return $userList;
    }

    public function assigntask(Request $request){
     $datatask=[
        'task_name' => $request['task_name'],
        'task_detail' => $request['task_detail'],
        'status' => 'New',
        'user_id'=> Auth::user()->id,
        'due_date'=> date('Y-m-d',strtotime($request['due_date'])),
        'assigned_id' => $request['assigned_id'],
        'priority' =>$request['priority'],
     ];
     $tempdatatask=$datatask;
     
     $datatask['comment'] = $request['comment'];
     $datatask['task_id'] = $request['taskid'];
     $datataskhistory = $datatask;
   
     $assignto=taskhistory::create($datataskhistory);
     $updateTaskto=task::whereId($datatask['task_id'])->update($tempdatatask);
     if($updateTaskto)
     return response()->json('Successfully created',200); 
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
            'status' => 'New',
            'user_id'=> Auth::user()->id,
            'due_date'=> date('Y-m-d',strtotime($request['due_date'])),
            'assigned_id' => $request['assigned_id'],
            'priority' =>$request['priority'],
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
    public function show($id)
    {
        //
        $task=task::leftjoin('users as u','u.id','=','tasks.user_id')->select('tasks.*',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as due_date'),DB::raw('DATE_FORMAT(tasks.created_at,"%d-%m-%Y") as assigned_on'),'u.id','u.first_name','u.last_name',DB::raw('tasks.id as taskid' ))->findorfail($id);
        return view('admin.taskshow',compact('task'));
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
    public function update(Request $request, $id)
    {
        //
        $data['task_name'] = $request->task_name;
        $data['task_detail'] = $request->task_detail;
        $data['status'] = $request->status;
        $data['due_date'] =  date('Y-m-d',strtotime($request->due_date));
        $data['assigned_id'] = $request->assigned_id;
        $data['priority'] = $request->priority;
        if(task::whereId($id)->update($data));
        return response()->json('Successfully updated',200);
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
