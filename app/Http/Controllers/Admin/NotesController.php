<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\notes;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
    }

    public function fetchNotes(request $request)
    {
        //
         $notes=notes::where('cust_id','=',$request->custId)->get();
         return response()->json($notes,200);
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
      $data['description']=$request->singleNote['description'];
      $data['cust_id']=$request->custId;
       try{
            notes::create($data);
            return response()->json("notes Created",200);
        }catch(\Exception $e){
            return response()->json("error",400);
        }
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
        $data['description']=$request->singleNote['description'];
        $data['cust_id']=$request->custId;
         try{
             notes::whereId($id)->update($data);
              return response()->json("notes updated",200);
          }catch(\Exception $e){
              return response()->json("error",400);
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
        $deletenote = notes::find($id);
        try{
            $deletenote->delete();
            return response()->json("Deleted successfully");
        }catch(\Exception $e){
            return response()->json("Can't be able to delete");
        }
    }
}
