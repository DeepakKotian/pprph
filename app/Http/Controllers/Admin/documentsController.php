<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Gate;
use DB;

class documentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.documents');
    }
    public function getDocFilterData(Request $request)
    {
       /* SELECT documents.id,documents.document_name, policy_documents.*, customers.first_name, inc.name as insurance_name, prd.name as provider_name FROM documents  LEFT JOIN policy_documents ON document_id = documents.id LEFT JOIN policy_detail ON policy_detail.id = policy_detail_id LEFT JOIN massparameter inc ON inc.id =  policy_detail.insurance_ctg_id LEFT JOIN massparameter prd ON prd.id =  policy_detail.provider_id LEFT JOIN customers ON customers.id = documents.customer_id */

        $document =  DB::table('documents')->select(['documents.customer_id','documents.id as docId','documents.document_name', 'policy_documents.*', 
        'customers.first_name as name', 'inc.name as insurance_name', 'prd.name as provider_name'])
        ->leftJoin('customers','customers.id','=','documents.customer_id')
        ->leftJoin('policy_documents','document_id','=','documents.id')
        ->leftJoin('policy_detail','policy_detail.id','=','policy_detail_id')
        ->leftJoin('massparameter as inc','inc.id','=','policy_detail.insurance_ctg_id')
        ->leftJoin('massparameter as prd','prd.id','=','policy_detail.provider_id');
        if(Auth::user()->role !== 1)
        {
            $document->where('customers.user_id',Auth::user()->id);
        } 
        $document = $document->groupBy('documents.id')->get();
        return Datatables::of($document)
            ->filter(function ($instance) use ($request) {
            if ($request->has('searchTerm') && $request->searchTerm!=null) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return Str::contains(strtolower($row['document_name']), strtolower($request->get('searchTerm'))) || Str::contains($row['name'], $request->get('searchTerm')) ? true : false;
                });
            }
        })->addIndexColumn()->make(true);;
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $document =  DB::table('documents')->select(['documents.customer_id','policy_detail.policy_number','documents.id','documents.document_name', 'policy_documents.*', 
        'customers.first_name as name', 'inc.name as insurance_name', 'prd.name as provider_name'])
        ->leftJoin('customers','customers.id','=','documents.customer_id')
        ->leftJoin('policy_documents','document_id','=','documents.id')
        ->leftJoin('policy_detail','policy_detail.id','=','policy_detail_id')
        ->leftJoin('massparameter as inc','inc.id','=','policy_detail.insurance_ctg_id')
        ->leftJoin('massparameter as prd','prd.id','=','policy_detail.provider_id')
        ->where('customers.id','=', $request->id)
        ->where('documents.id','=', $request->document_id)
        ->groupBy('policy_detail.policy_number')
        ->get();
        foreach ($document  as $key => $value) {
            $document->docName = $value->document_name;
        }
        $view = view('admin.ajaxdocument', [ 'documents' => $document ])->render();
        return response()->json(['data'=>$view]);
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
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if($request->id){
            $data = DB::table('policy_documents')
            ->where('policy_detail_id',$request->policy_detail_id)
            ->where('document_id',$request->document_id)->delete();
            return response()->json('Successfully Deleted',200);
        }
    }

    public function fetchCustomerDocs($id){
        if($id){
            $data = DB::table('documents')
            ->where('customer_id',$id)
            ->get();
            return response()->json($data,200); 
        }
    }

    public function deleteCustomerDocument(Request $request)
    {
        if($request->id){
            $data = DB::table('policy_documents')
            ->where('document_id',$request->id)->delete();
            DB::table('documents')
            ->where('id',$request->id)->delete();
            return response()->json('Successfully Deleted',200);
        }
    }

    public function uploadCustomerDocument(Request $request){
        if(!empty($request->file('documentData'))){
            $file = $request->file('documentData');
            $docName = $file->getClientOriginalName();
            $destinationPath = public_path('/uploads/vertrag');
            $file->move($destinationPath, $docName);
            $documentId = DB::table('documents')->insertGetId(['document_name'=>$docName,'title'=> $request->title, 'customer_id'=>$request->customer_id]);
            return response()->json('Successfully Added',200);
         }else{
            return response()->json('Document not selected',500);
        }
    }

    
}
