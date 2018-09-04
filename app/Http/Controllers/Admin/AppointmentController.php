<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\task;
use App\customer;
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
        $data = task::leftjoin('users as u','u.id','=','tasks.user_id')->leftjoin('customers as cst','cst.id','=','tasks.customer_id')
                ->select('tasks.task_name as title', 'u.first_name','u.last_name','cst.first_name as cfirst_name','cst.last_name as clast_name','tasks.assigned_id','tasks.task_detail as description','customer_id',DB::raw('tasks.due_date as end, tasks.start_date as start '),
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
        $startDate=date('Y-m-d H:i:s',strtotime($startDate));
        $endDate=date('Y-m-d H:i:s',strtotime($endDate));
        $data = [
            'task_name' => $request['title'],
            'task_detail' => $request['description'],
            'status' => 'Pending',
            'user_id'=>  $id,
            'type'=>'appointment',
            'customer_id'=> $request['customer_id'],
            'start_date'=> $startDate,
            'due_date'=> $endDate,
            'assigned_id' => $request['assigned_id'],
        ];
        if( $startDate < $endDate){

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
        }
        else{
            return response()->json('End date and time must greater than start date and time',400); 
        }
           return redirect()->back()->withErrors($validate->errors());
    }

    public function update(Request $request){
        $id = Auth::user()->id;
        $validate = $this->validate(request(),[
            'title' => 'required',
            'description' => 'required',
            'assigned_id' => 'required',
            ]
        );
        $startTime = $request['start_time']?$request['start_time']:'00';
        $endTime = $request['end_time']?$request['end_time']:'00';
        $editId =  $request->id;
        $startDate = $request['start_date'].' '.$startTime.':00';
        $endDate = $request['end_date'].' '.$endTime.':00';
        $startDate=date('Y-m-d H:i:s',strtotime($startDate));
        $endDate=date('Y-m-d H:i:s',strtotime($endDate));
        $data = [
            'task_name' => $request['title'],
            'task_detail' => $request['description'],
            'status' => 'Pending',
            'user_id'=>  $id,
            'type'=>'appointment',
            'customer_id'=> $request['customer_id'],
            'start_date'=> $startDate,
            'due_date'=>  $endDate,
            'assigned_id' => $request['assigned_id'],
        ];
        if( $startDate < $endDate){
       $updatedData = task::whereId($editId)->update($data);
       if($updatedData){
        $response = task::leftjoin('users as u','u.id','=','tasks.user_id')
        ->select('tasks.task_name as title', 'u.first_name','u.last_name','tasks.assigned_id', 'tasks.task_detail as description',DB::raw('tasks.due_date as end, tasks.start_date as start '),
                         DB::raw('tasks.id as id' ))
        ->where('type','=','appointment')
        ->where('tasks.id','=',$editId)
        ->get();
        return response()->json($response,200);
       }
    }
    else{
        return response()->json('End date and time must greater than start date and time',400);    
       }
       return redirect()->back()->withErrors($validate->errors());
    }

    public function fetchCustomersForAppointment(){

       $customers = customer::select('id','first_name','last_name')->where('is_family','=',0)->get();

        if($customers){
            return response()->json($customers,200);
        }
           
    }

    public function deleteAppointment(Request $request){
        $delete= task::find($request->appointmentId);
        $delete->delete();
        if($delete){
            return response()->json('Deleted Successfully',200); 
        }
    }
}
