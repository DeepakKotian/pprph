<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\massparameter;
use App\insurancemapped;
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
        return view('admin.insurancelist');
    }

    public function fetchInsurance()
    {
        $insuranceList = [];
        $insuranceList = DataTables::of(massparameter::where('type','=','category'))->toJson();
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
        $insertData = massparameter::create([
            'type' => $request['type'],
            'name' => $request['name'],
           
        ]);
        if($insertData)
        return response()->json('Successfully created',200);
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
        $data['name'] = $request->name;
        if(massparameter::whereId($id)->update($data));
        return response()->json('Successfully updated',200);
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
        ->select(DB::raw('inc.name as insurance_name'),DB::raw('prd.name as provider_name'),'insurance_mapped.id','insurance_ctg_id','provider_id','document_name'))->toJson();
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
        $duplicate =  $pmap->select('*')->where('insurance_ctg_id',$request['insure_id'])->where('provider_id','=',$request['policy_id'])->first();
       
        if(empty($duplicate)){
            $validate = $this->validate(request(),[
                'insure_id' => 'required',
                'policy_id' => 'required',
                ]
            );
            
            if($request->hasFile('documnetData')){
                $file=$request->file('documnetData');
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
             return response()->json('Policy Already Mapped', 500);
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

   
}
