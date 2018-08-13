<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\customer;
use DB;
use Auth;
use DataTables;

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
        $customer = customer::select(['id','first_name','last_name'])->get();
        $insuranceCtg = DB::table('massparameter')->where('type','category')->get();
        $arrClm = [];
        $cnt = $insuranceCtg->count()+4;
        for($i=5;$i<=$cnt;$i++){
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
    foreach ($insuranceCtg as $key => $value) {
        $jnQry .= " LEFT JOIN massparameter ctg{$key} ON pd.insurance_ctg_id= ctg{$key}.id  AND ctg{$key}.id=$value->id AND ctg{$key}.type='category' ";
        $name = preg_replace('/\s+/', '_', $value->name);
        $strArr[$key] = "(COUNT(IF(pd.insurance_ctg_id = ctg{$key}.id,1,NULL))) ctg{$key}";
    }
    $addQry =  implode(',',$strArr);

    $selectQry =  "SELECT c.id, c.first_name, c.last_name, c.email,c.city,c.nationality,c.zip, {$addQry} FROM customers c LEFT JOIN policy_detail pd ON pd.customer_id = c.id {$jnQry} WHERE c.is_family=0 GROUP BY c.id, c.first_name, c.last_name, c.email,c.city,c.nationality,c.zip ";      
    $customer =  DB::select(DB::raw($selectQry));

        return Datatables::of($customer)
            ->filter(function ($instance) use ($request) {
                if ($request->has('id')&& $request->id!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('id') ? true : false;
                    });
                }
                if ($request->has('name')&& $request->name!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                         return $row['id'] == $request->get('name') ? true : false;
                    });
                }
                if ($request->has('searchTerm')&& $request->searchTerm!=null) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return (Str::contains($row['zip'], $request->get('searchTerm')) || Str::contains($row['city'], $request->get('searchTerm')) 
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
            'email' => 'required|email|unique:customers', 
            'gender' => 'required',
            'language' => 'required',
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
            'nationality' => $request['nationality'],
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
        return view('admin.customerform',compact(['data','insuranceCtg']));
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
        $data->policy = DB::table('massparameter')->select(['massparameter.id','insurance_ctg_id'])
        ->leftJoin('policy_detail','policy_detail.insurance_ctg_id','=','massparameter.id')
        ->where('massparameter.type','category')
        ->where('customer_id',$request->id)
        ->groupBy('massparameter.id')
        ->get();
        $data->family = customer::select(['id','first_name','last_name',DB::raw('DATE_FORMAT(dob, "%d-%m-%Y") as dob'),'nationality'])
        ->where('is_family','1')
        ->where('parent_id',$request->id)
        ->get();
        
        $insuranceCtgArr = [];
        foreach ($data->policy as $key => $value) {
           $insuranceCtgArr[] = $value->insurance_ctg_id;
        }
        $data->policyArr =  $insuranceCtgArr;
            if(!empty($data)){
                $data->id = $request->id;
            }
        }
        return response()->json($data, 200);
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
        $checkAdmin =  $user->select('customers.email')->where('customers.email',$request->email)->where('customers.id','<>',$request->id)->first();
        if(empty($checkAdmin)){
            $data['first_name'] = $request->first_name;
            $data['last_name'] = $request->last_name;
            $data['email'] = $request->email;
            $data['email_office'] = $request->email_office;
            $data['telephone'] = $request->telephone;
            $data['mobile'] = $request->mobile;
            $data['zip'] = $request->zip;
            $data['dob'] = date('Y-m-d h:i:s',strtotime($request->dob));
            $data['nationality'] = $request->nationality;
            $data['address'] = $request->address;
            $data['company'] = $request->company;
            $data['gender'] = $request->gender;
            $data['language'] = $request->language;
            if(customer::whereId($request->id)->update($data));
            return response()->json('Successfully updated',200);
        }
        else{
            return response()->json('Email Aready Taken', 500);
        }  
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
        if(customer::whereId($request->id)->update($data));
        return response()->json('Successfully updated',200);
    }

    public function deleteFamily(Request $request){
        if(customer::whereId($request->id)->delete());
        return response()->json('Successfully deleted',200);
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
}
