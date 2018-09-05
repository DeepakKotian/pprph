<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\massparameter;
use App\insurancemapped;
use App\policydetail;
use App\customer;
use DataTables;
use Auth;
use Gate;
use DB;
class insuranceController extends Controller
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
        //
        $insuranceList= view('admin.insurancelist');
        if (Gate::denies('manage-admin', $insuranceList)) {
            return redirect('/admin');
        }
            else{
            return $insuranceList;
        }
    }

    public function fetchInsurance()
    {
        $insuranceList = [];
        $insuranceList = DataTables::of(massparameter::where('type','=','category')->orderby('id','desc'))->toJson();
        if($insuranceList)
            $fchinsuranceList= $insuranceList;
        if (Gate::denies('manage-admin', $fchinsuranceList)) {
            return redirect('/admin');
        }
            else{
            return $fchinsuranceList;
        }

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
            'name' => 'required',
            ]
        );
        $insertData = massparameter::firstOrCreate([
            'type' => $request['type'],
            'name' => preg_replace('!\s+!', ' ',$request['name']),
           
        ]);
       
        if($insertData->wasRecentlyCreated == true){
            return response()->json('Successfully created',200);
        }
        else{
            return response()->json('Insurance already created',400);
        }
       
        return redirect()->back()->withErrors($validate->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data['name'] = preg_replace('!\s+!', ' ',$request->name);
        $exist=massparameter::where('id','<>',$id)->where('name','=',$data['name'])->first();
        if($exist)
        {
            return response()->json('Provider Exists',400);
        }
        else{
            if(massparameter::whereId($id)->update($data))
            return response()->json('Successfully updated',200);
        }
          
       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function policyMapping()
    {
        return view('admin.insurancepolicymapped');
    }
    
    public function fetchPolicyMapping()
    {
        $mappingList = [];
        $mappingList = DataTables::of(insurancemapped::leftjoin('massparameter as inc','inc.id','=','insurance_ctg_id')->leftjoin('massparameter as prd','prd.id','=','provider_id')->groupby('insurance_ctg_id','provider_id')
        ->select('inc.name as insurance_name','prd.name as provider_name','insurance_mapped.id','insurance_ctg_id','provider_id','document_name')->where('inc.status',1)->where('prd.status',1)->orderby('insurance_mapped.id','desc'))->toJson();
        if($mappingList)
            $fchmappingList= $mappingList;
        if (Gate::denies('manage-admin', $fchmappingList)) {
            return redirect('/admin');
        }
            else{
              
            return $fchmappingList;
            dd($fchmappingList);
        }
    
    }

    public function addPolicyMapping(Request $request)
     {
        $pmap = DB::table('insurance_mapped');
        $duplicate = '';// $pmap->select('*')->where('insurance_ctg_id',$request['insure_id'])->where('provider_id','=',$request['policy_id'])->first();
       
        if(empty($duplicate)){
            $validate = $this->validate(request(),[
                'insure_id' => 'required',
                'policy_id' => 'required',
                'documnetData'=>'size:1000',
            ]);
            
            if($request->hasFile('documnetData')){
               
                $file=$request->file('documnetData');
                $filesize=$file->getClientSize();
               
                $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/antrag');
                $file->move($destinationPath, $imageName);
                $documnetData['documnetData'] = $imageName;
            }
            else{
                $documnetData['documnetData'] =null;
            }

            $insertData = insurancemapped::create([
                'insurance_ctg_id' => $request['insure_id'],
                'provider_id' => $request['policy_id'],
                'document_name' => $documnetData['documnetData'],
            
            ]);
            if($insertData)
            return response()->json('Successfully created',200);
            return redirect()->back()->withErrors($validate->errors());
            }
            else{
             return response()->json(['errors'=>'Policy Already Mapped'], 500);
            }  
    }

    public function updatePolicyMapping(Request $request){
        $pmap = DB::table('insurance_mapped');
    
        $duplicate =  $pmap->select('*')->where('insurance_ctg_id',$request['insure_id'])->where('provider_id','=',$request['policy_id'])->where('id','<>',$request->mappingId)->first();
     
        if(empty($duplicate)){

            if($request->hasFile('documnetData')){
                $file=$request->file('documnetData');
                $imageName = uniqid().time().'.'.$file->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/antrag');
                $file->move($destinationPath, $imageName);
                $documnetData['documnetData'] = $imageName;
            }
            else{
                $documnetData['documnetData']  =$request->documnetData;
            }

            $data['insurance_ctg_id'] = $request->insure_id;
            $data['provider_id'] =  $request->policy_id;
            $data['document_name'] =  $documnetData['documnetData'] ;
            if(insurancemapped::whereId($request->mappingId)->update($data));
            return response()->json('Successfully updated',200);
       
        }
        else{
            return response()->json('Policy Already Mapped', 500);
        }

    }

    public function updateStatus(Request $request)
    {
        $data['status'] = 1;
        if($request->status==1){
            $data['status'] = 0;
        }
        $json_data = massparameter::whereId($request->id)->update($data);
        return response()->json('Successfully updated status', 200);
    }

    public function policyDetail()
    {
        $customer =  policydetail::select(['customers.id','customers.unique_id','customers.first_name','customers.last_name'])
        ->leftJoin('customers','customer_id','=','customers.id')
        ->groupBy('customers.id')
        ->where('is_family','=',0)->get();
        $insuranceCtg = DB::table('massparameter')->where('type','category')->where('status',1)->get();
        return view('admin.policydetail',compact(['customer','insuranceCtg']));
    }

    public function policyFilterData(Request $request)
    {
        $policyDetail = policydetail::select('customers.id as custId','policy_detail.id as policy_detail_id','policy_detail.status as status',DB::raw('DATE_FORMAT(start_date, "%d-%m-%Y") as start_date'),DB::raw('DATE_FORMAT(end_date, "%d-%m-%Y") as end_date'),'first_name','last_name','telephone','email','city','zip', DB::raw('DATE_FORMAT(policy_detail.created_at, "%d-%m-%Y") as activation_date'), 'inc.name as insuranceName','prd.name as providerName')
        ->leftJoin('customers','customer_id','=','customers.id')
        ->leftJoin('massparameter as inc','inc.id','=','policy_detail.insurance_ctg_id')
        ->leftJoin('massparameter as prd','prd.id','=','policy_detail.provider_id');
        return Datatables::of($policyDetail) 
            ->filter(function ($instance) use ($request) {
            if ($request->has('id')&& $request->id!=null) {
                $instance->where('customers.id',$request->get('id'));
            }
            if ($request->has('name')&& $request->name!=null) {
                $instance->where('customers.id',$request->get('name'));
            }
            if ($request->has('searchTerm')&& $request->searchTerm!=null) {
                    $instance->whereRaw("
                        customers.first_name like '%{$request->searchTerm}%' 
                        OR customers.last_name like '%{$request->searchTerm}%' 
                        OR CONCAT(first_name,' ',last_name) like '%{$request->searchTerm}%'
                        OR customers.telephone like '%{$request->searchTerm}%'
                        OR customers.email like '%{$request->searchTerm}%'
                        OR customers.city like '%{$request->searchTerm}%'
                        OR customers.zip like '%{$request->searchTerm}%'
                        OR inc.name like '%{$request->searchTerm}%'
                        OR prd.name like '%{$request->searchTerm}%'
                        ");
            }
           
        })
        ->make(true);
    }

    public function policyStatus(Request $request){
        $dataChecked['status'] = 1;
        $data['status'] = 0;
        if($request->itemsChecked)
        $json_data = policydetail::whereIn('id',$request->itemsChecked)->update($dataChecked);
        if($request->items)
        $json_data = policydetail::whereIn('id',$request->items)->update($data);
        return response()->json('Successfully updated status', 200);
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
        $result = policydetail::select('customers.id','parent_id','first_name','last_name')
        ->leftJoin('customers','customer_id','=','customers.id')
        ->whereRaw( $str )->groupBy('customers.id')->get();
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

}
