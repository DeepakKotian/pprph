<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\task;
use DB;
use Auth;
use App\User;
use App\policydetail;
use DataTables;

class DashboardController extends Controller
{
    //
    public function fetchDueTask()
    {
   
        $dueTasklist = [];
        try{
          
            $query=task::leftjoin('users as u','u.id','=','tasks.user_id')->leftjoin('users as au','au.id','=','tasks.assigned_id')
            ->select('tasks.*','au.first_name as a_first_name','au.last_name as a_last_name',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as taskdue_date'),DB::raw('DATE_FORMAT(tasks.created_at,"%d-%m-%Y") as assigned_on'),'u.first_name','u.last_name',DB::raw('tasks.id as taskid'))
            ->where('tasks.type',NULL)->where('tasks.status','<>','Completed')->where('tasks.due_date','=' ,today())->orderby('tasks.created_at','DESC');

            if(Auth::user()->role !== 1)
            {
                $query->where('tasks.assigned_id',Auth::user()->id);
            }    
            $dueTasklist = DataTables::of($query)
            ->toJson();
            return $dueTasklist;
        }
        catch(\exception $e){
            return response()->json("No data found");
        }

    } 

    public function fetchDueAppointments(){
     
        $dueAppointments = [];
        try{
          
            $query=task::leftjoin('users as u','u.id','=','tasks.user_id')->leftjoin('customers as cst','cst.id','=','tasks.customer_id')
            ->select(DB::raw('DATE_FORMAT(tasks.created_at,"%d-%m-%Y") as created_on'),DB::raw('DATE_FORMAT(tasks.start_date,"%H:%i:00") as appointment_start_at'),DB::raw('DATE_FORMAT(tasks.due_date,"%H:%i:00") as appointment_end_at'),'tasks.task_name as title', 'u.first_name','u.last_name','cst.first_name as cfirst_name','cst.last_name as clast_name','tasks.assigned_id','tasks.task_detail as description','customer_id',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y") as end'), DB::raw('tasks.start_date as start'),
                             DB::raw('tasks.id as id' ))
            ->where('type','=','appointment')->whereDate('tasks.due_date', date('Y-m-d'))->orderby('tasks.created_at','DESC');

            if(Auth::user()->role !== 1)
            {
                $query->where('tasks.assigned_id',Auth::user()->id);
            }    
            $dueAppointments = DataTables::of($query)
            ->toJson();
            return $dueAppointments;
        }
        catch(\exception $e){
            return response()->json("No data found");
        }
    }

    public function fetchExpiredInsurance()
    {
        $dueInsurance = [];
     try{
        $query = policydetail::select('policy_detail.id as pid','c.id as cstId','c.first_name as cfirst_name','policy_number','c.last_name as clast_name','inc.name as ctgName',DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as pstart_date') ,DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as pend_date') ,'prd.name as providerName',DB::raw('DATE_FORMAT(end_date, "%d %M") as end_date'))
            ->leftJoin('customers as c','customer_id','=','c.id')
            ->leftJoin('massparameter as inc','inc.id','=','policy_detail.insurance_ctg_id')
            ->leftJoin('massparameter as prd','prd.id','=','policy_detail.provider_id')
            ->whereRaw('CURDATE() >= DATE_SUB(end_date, INTERVAL 20 DAY) AND CURDATE()<end_date');
     
            $dueInsurance = DataTables::of($query)
            ->toJson();
            return $dueInsurance;
        }
        catch(\exception $e){
            return response()->json("No data found");
        }
    
    }
}
