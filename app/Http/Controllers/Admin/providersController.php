<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\massparameter;
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
    public function index()
    {
        //
        return view('admin.providerslist');
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
