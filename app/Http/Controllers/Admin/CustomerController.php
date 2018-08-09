<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
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
    public function index()
    {
        $customer = customer::select(['id','first_name','last_name'])->get();
        $insuranceCtg = DB::table('massparameter')->where('type','category')->get();
        return view('admin.customers',compact(['customer','insuranceCtg']));
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

    $selectQry =  "SELECT c.id, c.first_name, c.last_name, c.email,c.city,c.nationality,c.zip, {$addQry} FROM customers c LEFT JOIN policy_detail pd ON pd.customer_id = c.id {$jnQry} GROUP BY c.id, c.first_name, c.last_name, c.email,c.city,c.nationality,c.zip ";      
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(customer $customer)
    {
        //
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
        //
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
