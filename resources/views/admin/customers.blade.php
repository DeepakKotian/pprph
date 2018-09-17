@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')   
   <h1>
Customers List
        <small>View list of customers</small>
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
              <h3 class="box-title">View Customers List </h3>
              <div class="box-tools">
                <a class="btn btn-primary btn-md pull-right" href="{{ url('admin/customer-form') }}">Add New</a>
                <a class="btn btn-primary btn-md" href="javascript:void(0)" id="printGrid"> PDF <i class="fa fa-download"> </i> </a> &nbsp;&nbsp;
                <a class="btn btn-primary btn-md" href="javascript:void(0)" id="printExcelGrid"> Excel <i class="fa fa-download"> </i> </a> &nbsp;&nbsp;
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
                    <div class="form-group">
                        <label class="left-15" for="selectJS">Product Status</label>
                        <select class="form-control selectJS" name="ctg">
                           <option value="">------</option>
                           @foreach($insuranceCtg as $ky=> $rwCtg)
                           <option value="{{ $ky }}">{{ $rwCtg->name }}</option>
                           @endforeach
                        </select>
                        <select class="form-control selectJS" name="status_prd">
                           <option value="">------</option>
                           <option value="0">Inactive</option>
                           <option value="1">Active</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="left-15" for="selectJS">Status</label>
                        <select class="form-control selectJS" name="status">
                           <option value="">------</option>
                           <option value="0">Inactive</option>
                           <option value="1">Active</option>
                        </select>
                    </div>
                    <div class="form-group">
                  
                     <button class="btn btn-danger left-15" id="resetFilter" type="reset" > Clear </button>
                    </div>
                </form>
            </div>
            <div class="col-12">
            <div class="table table-responsive">
                <table class="table table-bordered" id="customerTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>Postcode</th>
                                <th>Status</th>
                                @foreach($insuranceCtg as $key=> $rowCtg)
                                <th> {{ $rowCtg->name }}</th>
                                @endforeach
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
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Are you sure you want to delete <% currentUserId %> ?</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary"   v-on:click="deleteUser" type="button">Yes</a>
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
</style>

@stop

@section('js')
<script src="{!! asset('js/jquery-ui.js') !!}"></script>

<script>
  var oTable = $('#customerTable').DataTable({
        searching:false,
        processing: true,
        serverSide: true,
        order: [[ 0, "desc" ]],
        ajax: {
            url: '{{ url("admin/customer-filter-data") }}',
            data: function (d) {
                d.id = $('select[name=id]').val();
                d.name = $('select[name=name]').val();
                d.ctg = $('select[name=ctg]').val();
                d.statusPrd = $('select[name=status_prd]').val();
                d.status = $('select[name=status]').val();
                d.searchTerm = $('input[name=searchTerm]').val();
            }
        },
        columns: [
            {data: 'unique_id', name: 'unique_id'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'email', name: 'email'},
            {data: 'city', name: 'city'},
            {data: 'zip', name: 'zip'},
            {data: 'status', name: 'status'},
            @foreach($insuranceCtg as $key=> $rowCtg)
            {data: 'ctg{{ $key }}', name: 'ctg{{ $key }}'},
            @endforeach
        ],
        columnDefs:[{
            targets: [{{ $arrClms }}],
            data: null,
            render: function(data, type, full, meta){
                if(data>0){
                    if(type === 'display'){
                        data = '<span class="fa fa-square text-green" rel="ctg_'+meta.row+'_'+meta.col+'"></span>';
                       // data = '<input type="checkbox" class="icheckbox" name="ctg_'+meta.row+'_'+meta.col+'" value="'+data+'" checked >';
                    }
                }else{
                    if(type === 'display'){
                        data = '<span class="fa fa-square text-red" rel="ctg_'+meta.row+'_'+meta.col+'"></span>';
                       // data = '<input type="checkbox"  class="icheckbox" name="ctg_'+meta.row+'_'+meta.col+'"  value="'+data+'">';
                    }
                }
               return data;
            }
       },
       {
            targets: [6],
            data: null,
            render: function(data, type, full, meta){
                if(data>0){
                    if(type === 'display'){
                        data = '<span class="fa fa-check text-green" rel="ctg_'+meta.row+'_'+meta.col+'"></span>';
                       // data = '<input type="checkbox" class="icheckbox" name="ctg_'+meta.row+'_'+meta.col+'" value="'+data+'" checked >';
                    }
                }else{
                    if(type === 'display'){
                        data = '<span class="fa fa-times text-red" rel="ctg_'+meta.row+'_'+meta.col+'"></span>';
                       // data = '<input type="checkbox"  class="icheckbox" name="ctg_'+meta.row+'_'+meta.col+'"  value="'+data+'">';
                    }
                }
               return data;
            }
       }
    ]       
    });

   $(document).on('click', '#customerTable tbody tr', function () {
        var data = oTable.row(this).data();
       // window.location.href="/admin/customer-form/"+data.id;
          window.location.href="{{ url('admin/customer-form/') }}/"+data.id;
    });

    

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
                    url: '{{ url("admin/customer-search") }}',
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
                $('#searchTerm').val('');
                window.location.href= urlPrefix+"customer-form/"+ui.item.id;
             
            }
        });

        $('#printGrid').click(function(){
            $.ajax({
                    url: '{{ route("export.file") }}',
                    dataType: 'html',
                    type:'POST',
                    data: {
                        type:'pdf',
                        id :$('select[name=id]').val(),
                        name : $('select[name=name]').val(),
                        ctg :$('select[name=ctg]').val(),
                        statusPrd :$('select[name=status_prd]').val(),
                        status : $('select[name=status]').val(),
                        searchTerm : $('input[name=searchTerm]').val(),
                        _token:'{{ csrf_token() }}'
                    },
                    success: function( data ) {
                     
                        window.location.href = urlPrefix+"download-pdf";
                    }
            });


        });

        $('#printExcelGrid').click(function(){
            $.ajax({
                    url: '{{ route("export.file") }}',
                    dataType: 'html',
                    type:'POST',
                    data: {
                        type:'xlsx',
                        id :$('select[name=id]').val(),
                        name : $('select[name=name]').val(),
                        ctg :$('select[name=ctg]').val(),
                        statusPrd :$('select[name=status_prd]').val(),
                        status : $('select[name=status]').val(),
                        searchTerm : $('input[name=searchTerm]').val(),
                        _token:'{{ csrf_token() }}'
                    },
                    success: function( data ) {
                        window.location.href = urlPrefix+"download-excel";
                    }
            });


        });
       
    });
    $('#resetFilter').bind('click', function(){
            $('#searchTerm').val('');
            $('.select2-selection__rendered').text('------');
            $('select[name=name]')[0].selectedIndex = 0;
            $('select[name=id]')[0].selectedIndex = 0;
            $('select[name=ctg]')[0].selectedIndex = 0;
            $('select[name=status_prd]')[0].selectedIndex = 0;
            $('select[name=status]')[0].selectedIndex = 0;
            $('#customerTable').DataTable().search('').draw(); 
          
        });

</script>
@stop