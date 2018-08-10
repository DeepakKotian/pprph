<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\massparameter;
use DataTables;
use Auth;
use Gate;
class insuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
}
