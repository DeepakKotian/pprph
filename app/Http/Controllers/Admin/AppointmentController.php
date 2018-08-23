<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\task;
use DB;
use Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointment');
    }

    public function fetchAppointments()
    {
        $data = task::leftjoin('users as u','u.id','=','tasks.user_id')
                ->select('tasks.task_name as title', 'u.first_name','u.last_name','tasks.assigned_id','tasks.task_detail as description',DB::raw('tasks.due_date as end, tasks.start_date as start '),
                                 DB::raw('tasks.id as id' ))
                ->where('type','=','appointment')
                ->get();
        if($data)
        return $data;
    }

    public function store(Request $request){
        $id = Auth::user()->id;
        $validate = $this->validate(request(),[
            'title' => 'required',
            'description' => 'required',
            'assigned_id' => 'required',
            ]
        );
        $startTime = $request['start_time']?$request['start_time']:'00';
        $endTime = $request['end_time']?$request['end_time']:'00';

        $startDate = $request['start_date'].' '.$startTime.':00';
        $endDate = $request['end_date'].' '.$endTime.':00';
        $data = [
            'task_name' => $request['title'],
            'task_detail' => $request['description'],
            'status' => 'Pending',
            'user_id'=>  $id,
            'type'=>'appointment',
            'start_date'=> date('Y-m-d h:i:s',strtotime($startDate)),
            'due_date'=> date('Y-m-d h:i:s',strtotime($endDate)),
            'assigned_id' => $request['assigned_id'],
        ];
        $insertData = task::create($data);

        if($insertData){
            $response = task::leftjoin('users as u','u.id','=','tasks.user_id')
            ->select('tasks.task_name as title', 'u.first_name','u.last_name','tasks.assigned_id', 'tasks.task_detail as description',DB::raw('tasks.due_date as end, tasks.start_date as start '),
                             DB::raw('tasks.id as id' ))
            ->where('type','=','appointment')
            ->where('tasks.id','=',$insertData->id)
            ->get();
            return response()->json($response,200);
           }
           return redirect()->back()->withErrors($validate->errors());
    }
}
