<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\massparameter;
use Gate;
use DataTables;

class SettingsController extends Controller
{
    public function fetchLanguages(){
        $lgnList = [];
        $lgnList = DataTables::of(massparameter::where('type','=','language')->orderby('id','desc'))->toJson();
        if($lgnList)
            $fchlgnList= $lgnList;
        if (Gate::denies('manage-admin', $fchlgnList)) {
            return redirect('/admin');
        }
            else{
            return $fchlgnList;
        }
    }

    public function addLanguage(Request $request){

        $validate = $this->validate(request(),[
            'name' => 'required',
            'description'=>'required'
        ],[
            'name.required'=>'Enter language','description.required'=>'Enter language code'
        ]
        );
        $insertData = massparameter::firstOrCreate([
            'type' => $request['type'],
            'name' => preg_replace('!\s+!', ' ',$request['name']),
            'description'=>preg_replace('!\s+!', ' ',$request['description']),
           
        ]);
       
        if($insertData->wasRecentlyCreated == true){
            return response()->json('Successfully created',200);
        }
        else{
            return response()->json('Language already created',400);
        }
    }


    public function updateLanguage($id,Request $request){
        $data['name'] = preg_replace('!\s+!', ' ',$request->name);
        $data['description'] = preg_replace('!\s+!', ' ',$request->description);
        $exist=massparameter::where('id','<>',$id)->where('name','=',$data['name'])->where('type','=','language')->first();
        if($exist)
        {
            return response()->json('Language Exists',400);
        }
        else{
            if(massparameter::whereId($id)->update($data))
            return response()->json('Successfully updated',200);
        }  
    }

    public function updateLanguageStatus(Request $request){
        $data['status'] = 1;
        if($request->status==1){
            $data['status'] = 0;
        }
        $json_data = massparameter::whereId($request->lngid)->update($data);
        return response()->json('Successfully updated status', 200);
    }


}
