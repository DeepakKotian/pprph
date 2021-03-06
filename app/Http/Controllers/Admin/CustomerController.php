<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use PHPExcel_Worksheet_Drawing;
use Maatwebsite\Excel\Facades\Excel;
use App\customer;
use App\customerpolicymember;
use App\policydetail;
use App\User;
use App\task;
use App\customerlog;
use DB;
use Auth;
use DataTables;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');

    }
    
    public function index()
    {
        $customer = customer::select(['id','unique_id','first_name','last_name'])->where('is_family','=',0);
        if(Auth::user()->role !== 1)
        {
            $customer->where('customers.user_id',Auth::user()->id);
        } 
        $customer = $customer->get();
        $insuranceCtg = DB::table('massparameter')->where('type','category')->where('status',1)->orderby('order_by','asc')->get();
        
        $arrClm = [];
        $cnt = $insuranceCtg->count()+7;
        for($i=8;$i<=$cnt;$i++){
            $arrClm[]= $i;
        }
        $arrClms = implode(',',$arrClm); // For Dynamic Column JS looping
        $users = User::select('*')->get();
        return view('admin.customers',compact(['customer','insuranceCtg','arrClms','users']));
    }

    public function getCustomFilterData(Request $request)
    {

        /*  SELECT c.id,c.unique_id, c.first_name,c.user_id, c.last_name,c.status, c.email,c.city,c.nationality,c.zip,c.telephone,
(COUNT(IF(pd.insurance_ctg_id = ctg0.id,1,NULL))) ctg0, (COUNT(IF( pd.insurance_ctg_id = ctg0.id AND pd.status=1,1,NULL))) status0,
(COUNT(IF(pd.insurance_ctg_id = ctg1.id,1,NULL))) ctg1, (COUNT(IF(pd.insurance_ctg_id = ctg1.id AND pd.status=1,1,NULL))) status1,
(COUNT(IF(pd.insurance_ctg_id = ctg2.id,1,NULL))) ctg2, (COUNT(IF(pd.insurance_ctg_id = ctg2.id AND pd.status=1,1,NULL))) status2,
(COUNT(IF(pd.insurance_ctg_id = ctg3.id,1,NULL))) ctg3, (COUNT(IF(pd.insurance_ctg_id = ctg3.id AND pd.status=1,1,NULL))) status3
FROM customers c 
LEFT JOIN policy_detail pd ON pd.customer_id = c.id  
LEFT JOIN massparameter ctg0 ON pd.insurance_ctg_id= ctg0.id  AND ctg0.id=1 AND ctg0.type='category' AND pd.end_date>=CURDATE()  
LEFT JOIN massparameter ctg1 ON pd.insurance_ctg_id= ctg1.id  AND ctg1.id=2 AND ctg1.type='category' AND pd.end_date>=CURDATE()  
LEFT JOIN massparameter ctg2 ON pd.insurance_ctg_id= ctg2.id  AND ctg2.id=3 AND ctg2.type='category' AND pd.end_date>=CURDATE()  
LEFT JOIN massparameter ctg3 ON pd.insurance_ctg_id= ctg3.id  AND ctg3.id=4 AND ctg3.type='category' AND pd.end_date>=CURDATE()  
WHERE c.is_family=0  GROUP BY c.id, c.first_name, c.last_name ORDER BY c.id DESC */

    $jnQry='';
    $strArr = [];
    $insuranceCtg = DB::table('massparameter')->where('type','category')->where('status',1)->orderby('order_by','asc')->get();
    foreach ($insuranceCtg as $key => $value) { //Dynamic queries 
        $jnQry .= " LEFT JOIN massparameter ctg{$key} ON pd.insurance_ctg_id= ctg{$key}.id  AND ctg{$key}.id=$value->id AND ctg{$key}.type='category' AND pd.end_date>=CURDATE() ";
        $name = preg_replace('/\s+/', '_', $value->name);
        $strArr[$key] = "(COUNT(IF(pd.insurance_ctg_id = ctg{$key}.id,1,NULL))) ctg{$key},(COUNT(IF( pd.insurance_ctg_id = ctg{$key}.id AND pd.status=1,1,NULL))) status{$key} "; 
    }
    $addQry =  implode(',',$strArr);
    $count = $insuranceCtg->count();
    $customWhere = '';
    if(Auth::user()->role !== 1)
    {
      $customWhere = " AND c.user_id =".Auth::user()->id;
    }
    $selectQry =  "SELECT c.id,c.unique_id, c.first_name,c.user_id, c.last_name, CONCAT_WS(' ',users.first_name,users.last_name) as u_name, c.status, c.email,c.city,c.nationality,c.zip,c.telephone, {$addQry} FROM customers c LEFT JOIN users ON users.id = c.user_id LEFT JOIN policy_detail pd ON pd.customer_id = c.id {$jnQry} WHERE c.is_family=0 {$customWhere} GROUP BY c.id, c.first_name, c.last_name ORDER BY c.id DESC";      

    $customer =  DB::select(DB::raw($selectQry));

        return Datatables::of($customer)
            ->filter(function ($instance) use ($request) {
                if ($request->has('id')&& $request->id!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('id') ? true : false;
                    });
                }
                if ($request->has('user_id')&& $request->user_id!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['user_id'] == $request->get('user_id') ? true : false;
                    });
                }
                if ($request->has('name')&& $request->name!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('name') ? true : false;
                    });
                }
               //To get the product search dynamically
                if ($request->ctg!=null && $request->statusPrd!=null) { 
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if( $request->statusPrd==1){
                                return $row['ctg'.$request->ctg] > 0 ? true : false;
                            }else{
                                return $row['ctg'.$request->ctg] > 0 ? false : true;
                            }
                    });
                }

                if ($request->has('searchTerm')&& $request->searchTerm!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return (Str::contains($row['zip'], $request->get('searchTerm')) || Str::contains($row['email'], $request->get('searchTerm')) || Str::contains($row['city'], $request->get('searchTerm')) 
                        || Str::contains($row['first_name'], $request->get('searchTerm')) || Str::contains($row['telephone'], $request->get('searchTerm')) || Str::contains($row['last_name'], $request->get('searchTerm')) ) ? true : false;
                    });
                }
            })
            ->make(true);
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
        $uniqueId = 0;
        $data = customer::select('unique_id')->where('customers.is_family','0')->orderBy('unique_id','desc')->first();
        if(!empty($data)){
            $uniqueId = $data->unique_id;
        }
        $uniqueId = $uniqueId+1;
        $validate = $this->validate(request(),[
            'first_name' => 'required|min:3',
            //'email' => 'required|email|unique:customers', 
            'gender' => 'required',
            //'language' => 'required',
            ]
        );
        if($request['dob']!= null){
           $dob= date('Y-m-d h:i:s',strtotime($request['dob']));
        }
        else{
            $dob= NULL;
        }
        $insertData = customer::create([
            'first_name' => $request['first_name'],
            'unique_id'=>$uniqueId,
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'email_office' => $request['email_office'],
            'telephone' => $request['telephone'],
            'mobile' => $request['mobile'],
            'zip' => $request['zip'],
            'dob' => $dob, 
            'status' => $request['status'],
            'nationality' => $request['nationality'],
            'city' => $request['city'],
            'address' => $request['address'],
            'company' => $request['company'],
            'gender' => $request['gender'],
            'language' => $request['language'],
            'user_id'=> Auth::user()->id,
        ]);
        if($insertData)
        return response()->json($insertData,200);
        return redirect()->back()->withErrors($validate->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id='')
    {
        $data = [];

        if($id!=''){
            $data = customer::select('*')->where('customers.id',$id)->where('customers.is_family','0')->first();
            if(!empty($data)){
                $data->id = $id;
            }else{
                return redirect('/admin/customer-form');
            }
        }
        $insuranceCtg = DB::table('massparameter')->where('type','category')->orderby('order_by','asc')->get();
        $providers = DB::table('massparameter')->where('type','provider')->get();
        return view('admin.customerform',compact(['data','insuranceCtg','providers']));
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function fetchCustomer(request $request)
    {
        $data = [];
        if($request->id!=''){
            $data = customer::select('*')
            ->where('customers.is_family','0')
            ->where('customers.id',$request->id)
            ->first();
        if($data->dob != null)
        {
        $data->dob =  date('d-m-Y',strtotime($data->dob));
        }
        else{
            $data->dob="";
        }
        $data->insurance = DB::table('massparameter')->select(['id','type','name'])
        ->where('massparameter.type','category')
        ->where('status',1)
        ->groupBy('massparameter.id')
        ->orderby('massparameter.order_by','asc')
        ->get();
        $data->providers = DB::table('massparameter')->select(['id','type','name'])->where('type','provider')->where('status',1)->get();
        $data->policy = DB::table('massparameter')->select(['massparameter.id','insurance_ctg_id','provider_id'])
        ->leftJoin('policy_detail','policy_detail.insurance_ctg_id','=','massparameter.id')
        ->where('massparameter.type','category')
        ->where('end_date','>=',date('Y-m-d'))
        ->where('customer_id',$request->id)
        ->groupBy('massparameter.id')
        ->get();
        // $data->postcode = DB::table('postcode_city')->select('city','plz')->get();
        // $data->postcodeCity = DB::table('postcode_city')->select('city','plz')->groupBy('city')->get();
        $data->family = customer::select(['id','first_name','last_name','unique_id','mobile','email',DB::raw('DATE_FORMAT(dob, "%d-%m-%Y") as dob'),'nationality'])
        // ->where('is_family','1')
        ->where('parent_id',$request->id)
        ->get();
        $data->appointments = task::select('task_name','task_detail','assigned_id',DB::raw('DATE_FORMAT(tasks.due_date,"%d-%m-%Y %h:%I:%p") as end_date'), DB::raw('DATE_FORMAT(tasks.start_date,"%d-%m-%Y %h:%I:%p") as start_date'),DB::raw('CONCAT_WS(" ",users.first_name,users.last_name) as userName'))->where('customer_id',$request->id)
        ->leftJoin('users','users.id','=','tasks.assigned_id')  
        ->where('tasks.type','appointment')        
        ->whereRaw('CURDATE() >= DATE_SUB(tasks.start_date, INTERVAL 20 DAY) AND CURDATE()<tasks.start_date')
        ->get();
        
        $data->provision = policydetail::select( DB::raw('COUNT(insurance_ctg_id) insCount, (COUNT(IF(policy_detail.status=1,1,NULL))) as statusCount '),'insurance_ctg_id as id','provider_id')
        ->leftJoin('customers','customer_id','=','customers.id')
        ->where('end_date','>=',date('Y-m-d'))
        ->where('customers.id',$request->id)->groupBy('insurance_ctg_id')->get();

        $insuranceCtgArr = $providerArr =  $provisionArr = [];
        foreach ($data->policy as $key => $value) { //Looping policy array and adding insurance category and providers
           $insuranceCtgArr[] = $value->insurance_ctg_id;
           $providerArr[] =  $value->provider_id;
        }

        foreach ($data->provision as $ky => $val) { //Looping Provision array and Checking With Insurance count and status count of policy
            if($val['insCount']==$val['statusCount']){
                $provisionArr[] = $val['id'];
            }
        }

        $data->policyArr =  $insuranceCtgArr;
        $data->providerArr =  $providerArr;
        $data->provisionArr =  $provisionArr;

            if(!empty($data)){
                $data->id = $request->id;
            }
        }
        return response()->json($data, 200);
    }

    public function fetchPolicyDetail($id,Request $request)
    {

        $data = DB::table('policy_detail')->select(['policy_detail.id as policy_id', 'insurance_ctg_id', 'customers.id','policy_number',DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date'),DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date'),'insurance_ctg_id','provider_id','first_name'])
        ->leftJoin('customers','policy_detail.customer_id','=','customers.id')
        ->where('customer_id',$id)
        ->where('policy_detail.id',$request->policy_id)
        ->first();

        if($data){
            $family = DB::table('customer_policy_member')->select(['family_member_id'])
                            ->where('policy_detail_id',$data->policy_id)
                            ->get();
            $familyArr = $data->family = [];
            foreach ($family as $key => $value) {
                $familyArr[] = $value->family_member_id;
            }
            $data->family =  $familyArr;
        }
        if($data)
        return response()->json($data, 200);
        return response()->json('Policy details not found', 404);
    }

    public function fetchPolicyList($id,Request $request)
    {
        $data = DB::table('policy_detail')->select(['policy_detail.id as policy_id', 'insurance_ctg_id', 'customers.id','policy_number',DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date'),DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date'),'insurance_ctg_id','provider_id','first_name'])
        ->leftJoin('customers','policy_detail.customer_id','=','customers.id')
        ->where('customer_id',$id)
        ->where('insurance_ctg_id',$request->insurance_ctg_id)
        ->where('provider_id',$request->provider_id)
        ->get();
        if($data)
        return response()->json($data, 200);
        return response()->json('Policy details not found', 404);
    }

    public function savePolicy($id,Request $request)
    {
        $check = DB::table('policy_detail')->select(['policy_detail.id as policy_id', 'policy_number',DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date'),DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date'),'insurance_ctg_id','provider_id'])
        ->where('customer_id',$id)
        ->where('policy_detail.id',$request->policy_id)
        ->first();
        if(!empty($check)){
            $data['insurance_ctg_id'] = $request->insurance_ctg_id;
            $data['provider_id'] = $request->provider_id;
            $data['customer_id'] = $id;
           
            $data['start_date'] = date('Y-m-d',strtotime($request->start_date));
            $data['end_date'] = $request->end_date!=''?date('Y-m-d',strtotime( $request->end_date)):NULL;
            policydetail::whereId($check->policy_id)->update($data);
            customerpolicymember::where('policy_detail_id','=',$check->policy_id)->delete();
            if($request->family){
                foreach ($request->family as $key => $value) {
                    customerpolicymember::create(['policy_detail_id'=>$check->policy_id,'family_member_id'=>$value]);
                }
            } 
            /*$resCtg =  DB::table('massparameter')->select('name')->where('id',$data['insurance_ctg_id'])->where('type','category')->first();
            $resPrd =  DB::table('massparameter')->select('name')->where('id',$data['provider_id'])->where('type','provider')->first();
            $data['insuranceName'] = $resCtg->name;
            $data['providerName'] = $resPrd->name; 
            $arrCustomer['logs'] = serialize($data); // serialize should always kept on top to insert only form changes. 
            $arrCustomer['user_id'] = Auth::user()->id;
            $arrCustomer['customer_id'] =  $id;
            $arrCustomer['type'] = 'update_policy';
            customerlog::create($arrCustomer);*/

        }
         return response()->json('Successfully updated policy details',200);
    }

    public function addNewPolicy($id,Request $request)
    {
        $uniqueId = 0;
        $policy = policydetail::select('id')->orderBy('id','desc')->first();
        if($policy){
          $uniqueId = $policy->id;
        }
        $uniqueId = 'P0'.($uniqueId + 1); 
        $rand = rand();
        $data['insurance_ctg_id'] = $request->insurance_ctg_id;
        $data['provider_id'] = $request->provider_id;
        $data['customer_id'] = $id;
        $data['policy_number'] = $uniqueId;
        $data['start_date'] = date('Y-m-d',strtotime( $request->start_date));
        $data['end_date'] = $request->end_date!=''?date('Y-m-d',strtotime( $request->end_date)):NULL;
        $policy = policydetail::create($data);
        if($request->family){
            foreach ($request->family as $key => $value) {
               customerpolicymember::create(['policy_detail_id'=> $policy->id,'family_member_id'=>$value]);
            }
        }
       
        if($policy){
            $resCtg =  DB::table('massparameter')->select('name')->where('id',$data['insurance_ctg_id'])->where('type','category')->first();
            $resPrd =  DB::table('massparameter')->select('name')->where('id',$data['provider_id'])->where('type','provider')->first();
            $data['insuranceName'] = $resCtg->name;
            $data['providerName'] = $resPrd->name; 

            $arrCustomer['logs'] = serialize($data); // serialize should always kept on top to insert only form changes. 
            $arrCustomer['user_id'] = Auth::user()->id;
            $arrCustomer['customer_id'] =  $id;
            $arrCustomer['type'] = 'add_policy';
            customerlog::create($arrCustomer);
        }

        return response()->json('Successfully added new policy',200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request)
    {
        $data = [];
        $customer = $request->customer;
        $customer = array_slice($customer, 0,22);
        $oldCustomerData = $request->oldCustomerData;
        $result = array_diff_assoc($customer,$oldCustomerData);
       // dump($result);
        $arrCustomer =  [];

        foreach ($result as $key => $value) {
            if($key!='created_at' && $key!='updated_at'){
                    $arrCustomer[$key]['old_value'] =  $oldCustomerData[$key];
                    $arrCustomer[$key]['new_value'] = $customer[$key];
                    $data[$key] = $customer[$key];
            }
        }
        
        if(isset($data['dob'])){
            if($data['dob']!=null){
                $data['dob'] = date('Y-m-d h:i:s',strtotime($data['dob']));
            }
            else{
                $data['dob'] = NULL;
            }
        }

        if(!empty($arrCustomer)){
            $arrCustomer['logs'] = serialize($arrCustomer); // serialize should always kept on top to insert only form changes. 
            $arrCustomer['user_id'] = Auth::user()->id;
            $arrCustomer['customer_id'] = $customer['id'];
            $arrCustomer['type'] = 'personal';
            customerlog::create($arrCustomer);
        }

        if(!empty($data)){
            if(customer::whereId($customer['id'])->update($data));
            return response()->json('Successfully updated',200);
        }else{
            return response()->json('No changes done',500);
        }
 
    }

    public function storeFamily(Request $request)
    {
        $familyMemberCount = customer::where('parent_id',$request['parent_id'])->count() + 1;
        $parentData =  customer::select('unique_id')->where('id',$request['parent_id'])->first();
        $familyMemberId = $parentData['unique_id'].'.'.$familyMemberCount;
        if($request['dob_family']!=null)
        {
           $dob= date('Y-m-d h:i:s',strtotime($request['dob_family']));
        }
        else{
            $dob=null;
        }
        $insertData = customer::create([
            'first_name' => $request['first_name_family'],
            'last_name' => $request['last_name_family'],
            'is_family'=>1,
            'parent_id'=>$request['parent_id'],
            'dob' => $dob,
            'nationality' => $request['nationality_family'],
            'mobile' => $request['mobile_family'],
            'family_member_id' => $familyMemberId,
        ]);
        if($insertData){
            $arrCustomer['logs'] = serialize([
                'first_name' => $request['first_name_family'],
                'last_name' => $request['last_name_family'],
                'is_family'=>1,
                'parent_id'=>$request['parent_id'],
                'dob' => $dob,
                'nationality' => $request['nationality_family'],
                'mobile' => $request['mobile_family'],
                'family_member_id' => $familyMemberId,
            ]); // serialize should always kept on top to insert only form changes. 
            $arrCustomer['user_id'] = Auth::user()->id;
            $arrCustomer['customer_id'] = $request['parent_id'];
            $arrCustomer['type'] = 'add_family';
            customerlog::create($arrCustomer);
        }
        return response()->json('Successfully created',200); 
    }

    public function updateFamily(Request $request)
    {
        $data = [];
        $family = $request->family;
        $oldFamily = $request->oldFamily;
        $result = array_diff_assoc( $family,$oldFamily);
        $arrFamily =  [];
        foreach ($result as $key => $value) {
            if($key!='parent_id'){
                $arrFamily[$key]['old_value'] =  $oldFamily[$key];
                $arrFamily[$key]['new_value'] = $family[$key];
                if(isset($family['first_name_family']))
                $data['first_name'] = $family['first_name_family'];
                if(isset($family['last_name_family']))
                $data['last_name'] = $family['last_name_family'];
                if(isset($family['dob_family']))
                {
                    if($family['dob_family']!=null){
                        $data['dob']= date('Y-m-d h:i:s',strtotime($family['dob_family']));
                    }else{
                        $data['dob'] = NULL;
                    }
                }
                if(isset($family['nationality_family']))
                $data['nationality'] = $family['nationality_family'];
                if(isset($family['mobile_family']))
                $data['mobile'] = $family['mobile_family'];
            }
        }

        if(!empty($arrFamily)){
            $arrFamily['logs'] = serialize($arrFamily); // serialize should always kept on top to insert only form changes. 
            $arrFamily['user_id'] = Auth::user()->id;
            $arrFamily['customer_id'] = $family['parent_id'];
            $arrFamily['type'] = 'update_family';
            customerlog::create($arrFamily);
        }
        

        if(!empty($data)){
            if(customer::whereId($family['id'])->update($data));
            return response()->json('Successfully updated',200);
        }
        return response()->json('No Data Updated',500);
    }

    public function deleteFamily(Request $request){
        if(customer::whereId($request->id)->delete());
        return response()->json('Successfully deleted',200);
    }

    public function statusUpdate(Request $request){
       
        $data['status']=$request->currentStatusId;
    
        $json_data = customer::whereId($request->currentCustId)->update($data);
        return response()->json('Successfully '.$request->statusText.'ed', 200);
    }
    
    public function fetchDocuments(Request $request)
    {
        /*
         $data = policydetail::select(['policy_detail.id as policy_id', 'insurance_ctg_id','document_name', 'customers.id','policy_number',DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date'),DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date'),'insurance_ctg_id','provider_id','first_name'])
        ->leftJoin('customers','policy_detail.customer_id','=','customers.id')
        ->where('customer_id',$request->customer_id)
        ->where('policy_detail.id',$request->policy_id)
        ->first();
        if(!empty($data)){
            $addQry = ''; $docIds = [];
            $pDoc = DB::table('policy_documents')
            ->select('*')
            ->leftJoin('documents','document_id','=','documents.id')
            ->where('policy_detail_id', $data->policy_id)->get();
            foreach ($pDoc as $key => $value) {
                $docIds[] =  $value->document_id;
            }
            if(!empty($docIds)){
                $addQry = " id NOT IN (".implode(',',$docIds).") AND ";
            }
            $data->policyDocs = $pDoc;
            $allDocs = DB::table('documents')
            ->select('*')
            ->whereRaw(DB::raw( $addQry." customer_id=".$request->customer_id))->get();
            $data->allDocs = $allDocs;
        } */
        $data = DB::table('documents')
        ->select('*')
        ->where("customer_id",$request->customer_id)
        ->where("insurance_type",$request->insurance_ctg_id)
        ->get();
        $data->policyDocs =  $data;
        if($data)
        return response()->json($data, 200);
    }

    public function uploadDocuments(Request $request)
    {
        if($request->documentData!='undefined'){
                if(!$request->file('documentData')->getSize()){
                    return response()->json('Document should not be more than 2MB',500);
                }
        }
        if($request->documnetType!=0){ //This is not in issue because of new change.
            if(empty($request->document_id)){
                    if(!empty($request->file('documentData'))){
                        $file = $request->file('documentData');
                        $docName = $file->getClientOriginalName();
                        $destinationPath = public_path('/uploads/vertrag');
                        $file->move($destinationPath, $docName);
                       
                        $documentId = DB::table('documents')->insertGetId(['document_name'=>$docName,'customer_id'=>$request->customer_id]);
                        DB::table('policy_documents')->insert(['document_id'=>$documentId, 'policy_detail_id'=>$request->policy_id]);
                        
                    }else{
                        return response()->json('Document not selected',500);
                    }
                }else{
                    DB::table('policy_documents')->insert(['document_id'=>$request->document_id,'policy_detail_id'=>$request->policy_id]);
                }
            return response()->json('Successfully updated document',200);
           
        }else{ 
            if($request->documentData!='undefined'){
                $file = $request->file('documentData');
                $docName = $file->getClientOriginalName();
                $destinationPath = public_path('/uploads/vertrag');
                $file->move($destinationPath, $docName);
                DB::table('documents')->insert(['title'=>$request->title,'document_name'=>$docName,'customer_id'=>$request->customer_id,'insurance_type'=>$request->insureId]);
                return response()->json('Successfully updated document',200);
            }else{
      
                    return response()->json('Document not selected',404);
                
            }
       }
       //return redirect()->back()->withErrors($validate->errors());
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(customer $customer)
    {
        //
    }

    public function printCustomer($id)
    {
        // $path = public_path('js/countries.json'); 
        // $jsonCountry = json_decode(file_get_contents($path), true); 
        $data = customer::select('*')->where('customers.id',$id)->where('customers.is_family','0')->first();
        $data->policy = DB::table('policy_detail')->selectRaw("policy_detail.start_date,policy_detail.end_date,inc.name as insuranceName, prd.name as providerName")
        ->leftJoin('massparameter as inc', function ($join) {
            $join->on('inc.id', '=', 'policy_detail.insurance_ctg_id');
            $join->where('inc.type', '=', 'category');
        })->leftJoin('massparameter as prd', function ($join) {
            $join->on('prd.id', '=', 'policy_detail.provider_id');
            $join->where('prd.type', '=', 'provider');
        })
        ->where('end_date','>=',date('Y-m-d'))
        ->where('customer_id',$id)
        ->get();
        $data->family = customer::select(['id','first_name','last_name','mobile','email',DB::raw('DATE_FORMAT(dob, "%d-%m-%Y") as dob'),'nationality'])
        ->where('is_family','1')
        ->where('parent_id',$id)
        ->get();
        $pdf = PDF::loadView('admin.printcustomerdetail', ['data' => $data]);
        return $pdf->stream($data->first_name.'-'.$data->first_name.'-detail.pdf');
        //return $pdf->download('customer'.$id.'.pdf'); //To download 
    }

    public function search(Request $request)
    {
        $data = []; $str='';
        $terms = explode(' ',$request->term);
        foreach ($terms as $ky => $val) {
            $str.=" first_name like '%" . $val . "%' OR last_name like '%" . $val . "%' OR mobile like  '%" . $val . "%' OR telephone like  '%" . $val . "%' OR  dob like  '%" . $val . "%' OR " ;
        }
        if($str){
            $str = substr($str, 0, -3);
            $str =  "(". $str.")";
        }

        $result = customer::select('id','parent_id','first_name','last_name')->whereRaw( $str )->get();
        foreach ($result as $key => $value) {
            if($value['parent_id']>0){
               $data[$key]['id'] = $value['parent_id'];
            }else{
               $data[$key]['id'] = $value['id'];
            }
            $data[$key]['value'] = $value['first_name'].' '.$value['last_name'];
        }
        return response()->json($data,200);
    }
   
    public function exportFile(Request $request){

        $jnQry='';
        $strArr = [];
        $insuranceCtg = DB::table('massparameter')->where('type','category')->where('status',1)->orderby('order_by','asc')->get();
        foreach ($insuranceCtg as $key => $value) { //Dynamic queries 
            $jnQry .= " LEFT JOIN massparameter ctg{$key} ON pd.insurance_ctg_id= ctg{$key}.id  AND ctg{$key}.id=$value->id AND ctg{$key}.type='category'  AND pd.end_date>=CURDATE() ";
            $name = preg_replace('/\s+/', '_', $value->name);
            $strArr[$key] = "(COUNT(IF(pd.insurance_ctg_id = ctg{$key}.id,1,NULL))) ctg{$key},(COUNT(IF( pd.insurance_ctg_id = ctg{$key}.id AND pd.status=1,1,NULL))) status{$key}";
        }
        $addQry =  implode(',',$strArr);
        $count = $insuranceCtg->count();
        $customWhere = '';
        if(Auth::user()->role !== 1)
        {
          $customWhere = " AND c.user_id =".Auth::user()->id;
        }
        $selectQry =  "SELECT c.id,c.unique_id, c.first_name,c.user_id, c.last_name,c.status, c.email,c.city,c.nationality,c.zip,c.telephone,CONCAT_WS(' ',users.first_name,users.last_name) as u_name, {$addQry} FROM customers c LEFT JOIN users ON users.id = c.user_id LEFT JOIN policy_detail pd ON pd.customer_id = c.id {$jnQry} WHERE c.is_family=0 {$customWhere} GROUP BY c.id, c.first_name, c.last_name ORDER BY c.unique_id ASC";      

        $customer =  DB::select(DB::raw($selectQry));

        $data = Datatables::of($customer)
            ->filter(function ($instance) use ($request) {
                if ($request->has('id')&& $request->id!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('id') ? true : false;
                    });
                }
                if ($request->has('user_id')&& $request->user_id!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['user_id'] == $request->get('user_id') ? true : false;
                    });
                }
                if ($request->has('name')&& $request->name!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('name') ? true : false;
                    });
                }
               //To get the product search dynamically
                if ($request->ctg!=null && $request->statusPrd!=null) { 
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if( $request->statusPrd==1){
                                return $row['ctg'.$request->ctg] > 0 ? true : false;
                            }else{
                                return $row['ctg'.$request->ctg] > 0 ? false : true;
                            }
                    });
                }

                if ($request->has('searchTerm')&& $request->searchTerm!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return (Str::contains($row['zip'], $request->get('searchTerm')) || Str::contains($row['email'], $request->get('searchTerm')) || Str::contains($row['city'], $request->get('searchTerm')) 
                        || Str::contains($row['first_name'], $request->get('searchTerm')) || Str::contains($row['last_name'], $request->get('searchTerm')) ) ? true : false;
                    });
                }
            })
            ->toArray();
        $customer['table'] = $data['data'];
        
        $customer['ctgs'] =  $insuranceCtg;
        if($request->type=='pdf'){
           
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('admin.printcustomer', ['data' => $customer]);
      
            $filename = date('Y-m-d').'-customer-grid.pdf';
             file_put_contents(public_path('/uploads/customer/'.$filename), $pdf->output());

            return $filename;
        }else{
         
         
           $spreadsheet = new Spreadsheet();
           $row = 1;
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$row, 'ID');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2,$row, 'First Name');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3,$row, 'Last Name');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$row, 'Email');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$row, 'City');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,$row, 'Postal code');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$row, 'Telephone');
           $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(8,$row, 'Created By');
           $column = 9; //dynamic column starts 
           $ctgs = $customer['ctgs']->toArray();

           foreach ($ctgs as $ky => $val) {
             $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($column,$row, $val->name);  
             $column++;
           }
           $arrAlpha =  ['0'=>'I','1'=>'J','2'=>'K','3'=>'L','4'=>'M','5'=>'N']; //Adjust Columns
           $row++;
          
           $spreadsheet->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true )->setName('Arial');
           foreach ($customer['table'] as $key => $value) {
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$row, $value['unique_id']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2,$row, $value['first_name']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3,$row, $value['last_name']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$row, $value['email']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$row, $value['city']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,$row, $value['zip']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$row, $value['telephone']);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(8,$row, $value['u_name']);
            /* if($value['status']==0){
                $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $objDrawing->setPath(public_path('uploads/unchecked.png')); //your image path
                $objDrawing->setCoordinates('G'.$row);
                $objDrawing->setOffsetX(15);
                $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
            }else{
                $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $objDrawing->setPath(public_path('uploads/checked.png')); //your image path
                $objDrawing->setCoordinates('G'.$row);
                $objDrawing->setOffsetX(15);
                $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
            } */
            foreach ($ctgs as $ky => $val) {
                if($value['ctg'.$ky]>0){
                    $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $objDrawing->setPath(public_path('uploads/greenicon.png')); //your image path
                    $objDrawing->setOffsetX(15);
                    $objDrawing->setCoordinates($arrAlpha[$ky].$row);
                    $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
                    if($value['ctg'.$ky]==$value['status'.$ky]){
                        $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $objDrawing->setPath(public_path('uploads/checked.png')); //your image path
                        $objDrawing->setCoordinates($arrAlpha[$ky].$row);
                        $objDrawing->setOffsetX(35);
                        $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
                    }else{
                        $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $objDrawing->setPath(public_path('uploads/unchecked.png')); //your image path
                        $objDrawing->setCoordinates($arrAlpha[$ky].$row);
                        $objDrawing->setOffsetX(35);
                        $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
                    }
                }else{
                    $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $objDrawing->setPath(public_path('uploads/redicon.png')); //your image path
                    $objDrawing->setOffsetX(15);
                    $objDrawing->setCoordinates($arrAlpha[$ky].$row);
                    $objDrawing->setWorksheet($spreadsheet->getActiveSheet());
                }
            }
                
            $row++;
          }
          $filename = date('Y-m-d').'-customer-grid.xlsx';
          $writer = new Xlsx($spreadsheet);
          $writer->save('uploads/customer/'.$filename);
          return $filename;
        }
      } 

    public function downloadPDF(){
        $filename = date('Y-m-d').'-customer-grid.pdf';
        $path = public_path('/uploads/customer/'.$filename);
        header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
        header('Accept-Ranges: bytes');  // For download resume
        header('Content-Length: ' . filesize($path));  // File size
        header('Content-Encoding: none');
        header('Content-Type: application/pdf');  // Change this mime type if the file is not PDF
        header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
        readfile($path); 
    }

    public function downloadExcel(){
        $filename = date('Y-m-d').'-customer-grid.xlsx';
        $path = public_path('/uploads/customer/'.$filename);
        header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
        header('Accept-Ranges: bytes');  // For download resume
        header('Content-Length: ' . filesize($path));  // File size
        header('Content-Encoding: none');
        header('Content-Type: application/pdf');  // Change this mime type if the file is not PDF
        header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
        readfile($path); 
    }


    public function fetchLogs($id){
        $data = customerlog::select('customerlogs.*','customers.id',DB::raw('DATE_FORMAT(customerlogs.updated_at, "%d-%m-%Y %h:%i %p") as updatedAt'),DB::raw("CONCAT_WS(' ',users.first_name,users.last_name) as userName"))
        ->where('customer_id',$id)
        ->leftJoin('users','users.id','=','customerlogs.user_id')
        ->leftJoin('customers','customers.id','=','customerlogs.customer_id')
        ->orderBy('customerlogs.id','DESC')
        ->limit(100)->get();
       
        foreach ($data as $key => $value) {
           $data[$key]->logArr = unserialize($value['logs']); 
        }
        return response()->json($data,200);
    }

    public function saveAsCustomer(Request $request){
        $data = customer::select('unique_id')->where('customers.is_family','0')->orderBy('unique_id','desc')->first();
        if(!empty($data)){
            $uniqueId = $data->unique_id;
        }
        $uniqueId = $uniqueId+1;
        $data = customer::where('id',$request->id)->update([
            'first_name' => $request['first_name_family'],
            'unique_id'=> $uniqueId,
            'last_name' => $request['last_name_family'],
            'mobile' => $request['mobile_family'],
            'is_family'=>'0',
            'user_id'=> Auth::user()->id
        ]);
        return response()->json($request->id,200);
    }

    public function postCodeMap(Request $request){
        $data = DB::table('postcode_city')->select(['id','city'])->where('plz',$request->zip)->first();
        return response()->json($data->city,200);
    }
  
   
}
