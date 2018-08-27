<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\customer;
use App\customerpolicymember;
use App\policydetail;
use DB;
use Auth;
use DataTables;
use PDF;

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
        $customer = customer::select(['id','first_name','last_name'])->where('is_family','=',0)->get();
        $insuranceCtg = DB::table('massparameter')->where('type','category')->get();
        
        $arrClm = [];
        $cnt = $insuranceCtg->count()+5;
        for($i=6;$i<=$cnt;$i++){
            $arrClm[]= $i;
        }
        $arrClms = implode(',',$arrClm);
        return view('admin.customers',compact(['customer','insuranceCtg','arrClms']));
    }

    public function getCustomFilterData(Request $request)
    {

        /*  SELECT cust.id, cust.first_name, 
        (COUNT(IF(pd.insurance_ctg_id=auto.id,1, NULL))) 'Auto',
        (COUNT(IF(pd.insurance_ctg_id=LI.id,1, NULL))) 'Krankenkasse',
        (COUNT(IF(pd.insurance_ctg_id=HI.id,1, NULL))) 'Lebensversicherung'
        FROM customers cust
            LEFT JOIN policy_detail pd ON pd.customer_id = cust.id 
            LEFT  JOIN massparameter auto ON  pd.insurance_ctg_id=auto.id and auto.id=1 and auto.type='category'
                LEFT OUTER JOIN massparameter HI ON  pd.insurance_ctg_id=HI.id and HI.id=2 and HI.type='category'   
                LEFT OUTER JOIN massparameter LI ON  pd.insurance_ctg_id=LI.id and LI.id=3 and LI.type='category'
        GROUP BY cust.id, cust.first_name */
    $jnQry='';
    $strArr = [];
    $insuranceCtg = DB::table('massparameter')->where('type','category')->get();
    foreach ($insuranceCtg as $key => $value) { //Dynamic queries 
        $jnQry .= " LEFT JOIN massparameter ctg{$key} ON pd.insurance_ctg_id= ctg{$key}.id  AND ctg{$key}.id=$value->id AND ctg{$key}.type='category' ";
        $name = preg_replace('/\s+/', '_', $value->name);
        $strArr[$key] = "(COUNT(IF(pd.insurance_ctg_id = ctg{$key}.id,1,NULL))) ctg{$key}";
    }
    $addQry =  implode(',',$strArr);
    $count = $insuranceCtg->count();
    $selectQry =  "SELECT c.id, c.first_name, c.last_name,c.status, c.email,c.city,c.nationality,c.zip, {$addQry} FROM customers c LEFT JOIN policy_detail pd ON pd.customer_id = c.id {$jnQry} WHERE c.is_family=0 GROUP BY c.id, c.first_name, c.last_name ";      
    $customer =  DB::select(DB::raw($selectQry));

        return Datatables::of($customer)
            ->filter(function ($instance) use ($request) {
                if ($request->has('id')&& $request->id!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('id') ? true : false;
                    });
                }
                if ($request->has('status')&& $request->status!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['status'] == $request->get('status') ? true : false;
                    });
                }
                if ($request->has('name')&& $request->name!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('name') ? true : false;
                    });
                }
               //To get the product search dynamically
                if ($request->has('ctg')&& $request->ctg!=null) { 
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return $row['ctg'.$request->ctg] > 0 ? true : false;
                    });
                }

                if ($request->has('searchTerm')&& $request->searchTerm!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return (Str::contains($row['zip'], $request->get('searchTerm')) || Str::contains($row['email'], $request->get('searchTerm')) || Str::contains($row['city'], $request->get('searchTerm')) 
                        || Str::contains($row['first_name'], $request->get('searchTerm')) || Str::contains($row['last_name'], $request->get('searchTerm')) ) ? true : false;
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

        $validate = $this->validate(request(),[
            'first_name' => 'required|min:3',
            //'email' => 'required|email|unique:customers', 
            'gender' => 'required',
            'dob'=>'required|date',
            //'language' => 'required',
            ]
        );
        $insertData = customer::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'email_office' => $request['email_office'],
            'telephone' => $request['telephone'],
            'mobile' => $request['mobile'],
            'zip' => $request['zip'],
            'dob' => $request['dob'],
            'status' => $request['status'],
            'nationality' => $request['nationality'],
            'city' => $request['city'],
            'address' => $request['address'],
            'company' => $request['company'],
            'gender' => $request['gender'],
            'language' => $request['language'],
        ]);
        if($insertData)
        return response()->json('Successfully created',200);
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
        $insuranceCtg = DB::table('massparameter')->where('type','category')->get();
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
        $data->dob =  date('m/d/Y',strtotime($data->dob));
        $data->insurance = DB::table('massparameter')->select(['id','type','name'])
        ->where('massparameter.type','category')
        ->groupBy('massparameter.id')
        ->get();
        $data->providers = DB::table('massparameter')->select(['id','type','name'])->where('type','provider')->get();
        $data->policy = DB::table('massparameter')->select(['massparameter.id','insurance_ctg_id','provider_id'])
        ->leftJoin('policy_detail','policy_detail.insurance_ctg_id','=','massparameter.id')
        ->where('massparameter.type','category')
        ->where('customer_id',$request->id)
        ->groupBy('massparameter.id')
        ->get();
        $data->family = customer::select(['id','first_name','last_name','mobile','email',DB::raw('DATE_FORMAT(dob, "%d-%m-%Y") as dob'),'nationality'])
        ->where('is_family','1')
        ->where('parent_id',$request->id)
        ->get();
        
        $insuranceCtgArr = $providerArr = [];
        foreach ($data->policy as $key => $value) {
           $insuranceCtgArr[] = $value->insurance_ctg_id;
           $providerArr[] =  $value->provider_id;
        }
        $data->policyArr =  $insuranceCtgArr;
        $data->providerArr =  $providerArr;
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
            $data['policy_number'] = $request->policy_number;
            $data['start_date'] = date('Y-m-d',strtotime($request->start_date));
            $data['end_date'] = $request->end_date!=''?date('Y-m-d',strtotime( $request->end_date)):NULL;
            policydetail::whereId($check->policy_id)->update($data);
            if($request->family){
                customerpolicymember::where('policy_detail_id','=',$check->policy_id)->delete();
                foreach ($request->family as $key => $value) {
                customerpolicymember::create(['policy_detail_id'=>$check->policy_id,'family_member_id'=>$value]);
                }
            }  
        }
         return response()->json('Successfully updated policy details',200);
    }

    public function addNewPolicy($id,Request $request)
    {
        $data['insurance_ctg_id'] = $request->insurance_ctg_id;
        $data['provider_id'] = $request->provider_id;
        $data['customer_id'] = $id;
        $data['policy_number'] = $request->policy_number;
        $data['start_date'] = date('Y-m-d',strtotime( $request->start_date));
        $data['end_date'] = $request->end_date!=''?date('Y-m-d',strtotime( $request->end_date)):NULL;
        $policy = policydetail::create($data);
        if($request->family){
            foreach ($request->family as $key => $value) {
               customerpolicymember::create(['policy_detail_id'=> $policy->id,'family_member_id'=>$value]);
            }
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
    
    public function update(Request $request, customer $customer)
    {
        $user = DB::table('customers');
        //$checkAdmin =  $user->select('customers.email')->where('customers.email',$request->email)->where('customers.id','<>',$request->id)->first();
        //if(empty($checkAdmin)){
            $data['first_name'] = $request->first_name;
            $data['last_name'] = $request->last_name;
            $data['email'] = $request->email;
            $data['email_office'] = $request->email_office;
            $data['telephone'] = $request->telephone;
            $data['mobile'] = $request->mobile;
            $data['zip'] = $request->zip;
            $data['dob'] = date('Y-m-d h:i:s',strtotime($request->dob));
            $data['nationality'] = $request->nationality;
            $data['city'] =   $request->city;
            $data['status'] =   $request->status;
            $data['address'] = $request->address;
            $data['company'] = $request->company;
            $data['gender'] = $request->gender;
            $data['language'] = $request->language;
            if(customer::whereId($request->id)->update($data));
            return response()->json('Successfully updated',200);
        //}
        // else{
        //     return response()->json('Email Aready Taken', 500);
        // }  
    }

    public function storeFamily(Request $request)
    {
        $insertData = customer::create([
            'first_name' => $request['first_name_family'],
            'last_name' => $request['last_name_family'],
            'is_family'=>1,
            'parent_id'=>$request['parent_id'],
            'dob' => date('Y-m-d h:i:s',strtotime($request['dob_family'])),
            'nationality' => $request['nationality_family'],
            'mobile' => $request['mobile_family'],
        ]);
        if($insertData)
        return response()->json('Successfully created',200); 
    }

    public function updateFamily(Request $request)
    {
        
        $data['first_name'] = $request->first_name_family;
        $data['last_name'] = $request->last_name_family;
        $data['dob'] = date('Y-m-d h:i:s',strtotime($request->dob_family));
        $data['nationality'] = $request->nationality_family;
        $data['mobile'] = $request->mobile_family;
        if(customer::whereId($request->id)->update($data));
        return response()->json('Successfully updated',200);
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
        }
        if($data)
        return response()->json($data, 200);
    }

    public function uploadDocuments(Request $request)
    {
        
        if($request->documnetType!=0){
            if(empty($request->document_id)){
                    if(!empty($request->file('documentData'))){
                        $file = $request->file('documentData');
                        $docName = $file->getClientOriginalName();
                        $documentId = DB::table('documents')->insertGetId(['document_name'=>$docName,'customer_id'=>$request->customer_id]);
                        $destinationPath = public_path('/uploads/vertrag');
                        $file->move($destinationPath, $docName);
                        DB::table('policy_documents')->insert(['document_id'=>$documentId, 'policy_detail_id'=>$request->policy_id]);
                    }else{
                        return response()->json('Document not selected',404);
                    }
                }else{
                    DB::table('policy_documents')->insert(['document_id'=>$request->document_id,'policy_detail_id'=>$request->policy_id]);
                }
            return response()->json('Successfully updated document',200);
        }else{
            if($request->hasFile('documentData')){
                $file = $request->file('documentData');
                $docName = $file->getClientOriginalName();
                $destinationPath = public_path('/uploads/vertrag');
                $file->move($destinationPath, $docName);
                policydetail::whereId($request->policy_id)->update(['document_name'=>$docName ]);
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
        $data = customer::select('*')->where('customers.id',$id)->where('customers.is_family','0')->first();
        $pdf = PDF::loadView('admin.printcustomer', ['data' => $data]);
        return $pdf->stream($data->first_name.'-'.$data->first_name.'-detail.pdf');
        //return $pdf->download('customer'.$id.'.pdf'); //To download 
    }
}
