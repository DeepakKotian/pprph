@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')   
   <h1>
Policy List
        <small>View list of Policies</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      
        <li class="active">Customers List</li>
      </ol>

@stop

@section('content')

            <!-- /.box-header -->
            
            <div class="box box-primary" id="users-app">
  
        <!-- Breadcrumbs-->
        <div class="box-header">
              <h3 class="box-title">View Policies List </h3>
              <div class="box-tools">
                
              </div>
        </div>
    <div class="box-body">
            <div class="form-group">
               <form method="POST" id="search-form" class="form-inline" role="form">
                  <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchTerm" id="searchTerm" placeholder="Quick Search">
                            <div class="input-group-btn"><button type="submit" class="btn btn-primary btn-flat"> <i class="fa fa-search"></i> </button></div> </div>
                  </div>
                  <div class="form-group">
                        <label class="left-15" for="id">Name Search</label>
                        <select class="form-control selectJS" name="name">
                           <option value="">------</option>
                           @foreach($customer as $key=> $row)
                           <option value="{{ $row->id }}">   {{ $row->first_name }} {{ $row->last_name }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="left-15" for="id">Customer ID</label>
                        <select class="form-control selectJS" name="id">
                           <option value="">------</option>
                           @foreach($customer as $key=> $row)
                           <option value="{{ $row->id }}">   {{ $row->unique_id }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="form-group left-15">
                        <button class="btn btn-primary" style="" type="button" id="provisionButton" onclick="this.preventDefault"> Update Status </button>
                    </div>
                </form>
            </div>
            <div class="col-12">
            
            <div class="table table-responsive">
                <table class="table table-bordered" id="policyDetailTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Status</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>Postcode</th>
                                <th>Product</th>
                                <th>Provider</th>
                                <th>Activation date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                   
                        </tbody>				
                </table>
             </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal-->
<div class="modal fade" id="provisionModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Provision?
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
           </h5>
          </div>
          <div class="modal-body">Are you sure want to update the selected policy status ?</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" id="provisionModalButton" type="button">Yes</a>
          </div>
        </div>
      </div>
</div>



@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }} ">
<style>
   .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    display: none;
    min-width: 160px;   
    padding: 4px 0;
    margin: 0 0 10px 25px;
    list-style: none;
    background-color: #ffffff;
    border-color: #ccc;
    border-color: rgba(0, 0, 0, 0.2);
    border-style: solid;
    border-width: 1px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    *border-right-width: 2px;
    *border-bottom-width: 2px;
}

.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
}

.ui-state-hover, .ui-state-active {
    color: #ffffff;
    text-decoration: none;
    background-color: #3c8dbc;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    background-image: none;
}

.ui-state-active,
.ui-widget-content .ui-state-active,
.ui-widget-header .ui-state-active,
a.ui-button:active,
.ui-button:active,
.ui-button.ui-state-active:hover {
	border: 1px solid #3c8dbc;
	background: #3c8dbc;
	font-weight: normal;
	color: #ffffff;
}

   .checkbox label {
      display: inline-block;
      position: relative;
      padding-left: 5px; }
.checkbox label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid #cccccc;
        border-radius: 3px;
        background-color: #fff;
        -webkit-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
        transition: border 0.15s ease-in-out, color 0.15s ease-in-out; }
.checkbox label::after {
        display: inline-block;
        position: absolute;
        width: 16px;
        height: 16px;
        left: 0;
        top: 0;
        margin-left: -20px;
        padding-left: 3px;
        padding-top: 1px;
        font-size: 11px;
        color: #555555; 
}
.checkbox input[type="checkbox"] {
      opacity: 0; 
}
.checkbox input[type="checkbox"]:focus + label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
}
.checkbox input[type="checkbox"]:checked + label::after {
        font-family: 'FontAwesome';
        content: "\f00c";
}
.checkbox input[type="checkbox"]:disabled + label {
        opacity: 0.65; 
}
.checkbox input[type="checkbox"]:disabled + label::before {
          background-color: #eeeeee;
          cursor: not-allowed; 
}
.checkbox.checkbox-circle label::before {
      border-radius: 50%; 
}
.checkbox.checkbox-inline {
      margin-top: 0;
}
.checkbox-primary input[type="checkbox"]:checked + label::before {
    background-color: #428bca;
    border-color: #428bca; 
}
.checkbox-primary input[type="checkbox"]:checked + label::after {
    color: #fff; 
}

</style>

@stop

@section('js')
<script src="{!! asset('js/jquery-ui.js') !!}"></script>
<script>
  var oTable = $('#policyDetailTable').DataTable({
        searching:false,
        processing: true,
        serverSide: true,
        order: [[ 0, "desc" ]],
        ajax: {
            url: '{{ url("admin/policy-filter-data") }}',
            data: function (d) {
                d.id = $('select[name=id]').val();
                d.name = $('select[name=name]').val();
                d.statusPrd = $('select[name=status_prd]').val();
                d.searchTerm = $('input[name=searchTerm]').val();
            }
        },
        columns: [
            {data: 'policy_detail_id', name: 'policy_detail_id'},
            {data: 'status', name: 'status'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'telephone', name: 'telephone'},
            {data: 'email', name: 'email'},
            {data: 'city', name: 'city'},
            {data: 'zip', name: 'zip'},
            {data: 'insuranceName', name: 'status'},
            {data: 'providerName', name: 'status'},
            {data: 'activation_date', name: 'activation_date'},
            {data: 'start_date', name: 'end_date'},
            {data: 'end_date', name: 'end_date'},
           
        ],
        columnDefs:[
       {
            targets: [0],
            data: null,
            render: function(data, type, full, meta){
                if(full.status>0){
                    if(type === 'display'){
                        data = '<div class="checkbox checkbox-primary"><input class="provisionCheck" type="checkbox" id="ctg_'+meta.row+'_'+meta.col+'" name="ctg_'+meta.row+'_'+meta.col+'" value="'+data+'" checked ><label for="ctg_'+meta.row+'_'+meta.col+'"></label></div>';
                    }
                }else{
                    if(type === 'display'){
                       data = '<div class="checkbox checkbox-primary"><input  class="provisionCheck" type="checkbox"  class="" id="ctg_'+meta.row+'_'+meta.col+'" name="ctg_'+meta.row+'_'+meta.col+'"  value="'+data+'"><label for="ctg_'+meta.row+'_'+meta.col+'"></label></div>';
                    }
                }
               return data;
            }
       },
       {
            targets: [1],
            data: null,
            render: function(data, type, full, meta){
                if(full.status>0){
                    if(type === 'display'){
                        data = 'Provision';
                       // data = '<input type="checkbox" class="icheckbox" name="ctg_'+meta.row+'_'+meta.col+'" value="'+data+'" checked >';
                    }
                }else{
                    if(type === 'display'){
                        data = 'Open';
                       // data = '<input type="checkbox"  class="icheckbox" name="ctg_'+meta.row+'_'+meta.col+'"  value="'+data+'">';
                    }
                }
               return data;
            }
       }
    ]       
    });


   $(document).on('click', '#provisionButton', function () {
            $('#provisionModal').modal('show');
 
   });

  $(document).on('click','.provisionCheck', function () {
            $('#provisionButton').show();
   });

    $(document).on('click', '#provisionModalButton', function () {
        var itemsChecked = [], items = [];
        $('#policyDetailTable td .provisionCheck').each(function(index,vl){
            if(vl.checked==true){
                itemsChecked.push($(this).val());
            }else{
                items.push($(this).val());
            }
        });
   
       $.ajax({
           url:'{{ url("admin/update-policy-status") }}',
           dataType:'json',
           data: {items:items,itemsChecked:itemsChecked},
            success: function(data) {
                oTable.draw();
                $('#provisionModal').modal('hide');
            }
        })
    })


    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    $(document).ready(function() {
    
        $('.selectJS').select2();

        $('.selectJS').on('change', function (e) {
           $(this).closest('form').submit();
        });
        
        $( "#searchTerm" ).autocomplete({
            appendTo: "#search-form",
            source: function( request, response ) {
                $.ajax({
                    url: '{{ url("admin/policy-search") }}',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                $('#searchTerm').val(ui.item.value);
                $('#search-form').submit();
                //window.location.href= urlPrefix+"customer-form/"+ui.item.id;
                //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
            }
        });   
    });


</script>
@stop