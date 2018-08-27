<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\massparameter;
use App\insurancemapped;

use DataTables;
use Auth;
use Gate;
class providersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function index()
    {
        //
        return view('admin.providerslist');
    }

    public function fetchProvidersList(Request $request){
       
       // SELECT provider_id,name FROM `insurance_mapped` left join massparameter on provider_id=massparameter.id WHERE insurance_ctg_id= 1
        $providerList=insurancemapped::leftjoin('massparameter as ms','ms.id','=','provider_id')->select('provider_id','ms.name as providerName','document_name')->where('insurance_ctg_id','=',$request->insureId)
        ->groupBy('provider_id')
        ->get();
        return response()->json($providerList,200);
    }

    public function fetchProvider()
    {
        $providerlist = [];
        $providerlist = DataTables::of(massparameter::where('type','=','provider'))->toJson();
        if($providerlist)
            $fchproviderlist= $providerlist;
        if (Gate::denies('manage-admin', $fchproviderlist)) {
            return redirect('/admin');
        }
            else{
            return $fchproviderlist;
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
        //
        $validate = $this->validate(request(),[
            'name' => 'required',
            ]
        );
        $insertData = massparameter::firstOrCreate([
            'type' => $request['type'],
            'name' => $request['name'],
           
        ]);
        if($insertData->wasRecentlyCreated == true){
            return response()->json('Successfully created',200);
        }
        else{
            return response()->json('Providers already created',400);
        }
       
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
}
