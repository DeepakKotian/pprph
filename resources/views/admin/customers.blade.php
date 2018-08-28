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
              </div>
        </div>
    <div class="box-body">
            <div class="form-group">
               <form method="POST" id="search-form" class="form-inline" role="form">
                    <div class="form-group">
                        
                        <input type="text" class="form-control" name="searchTerm" id="searchTerm" placeholder="Quick Search">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
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
                           <option value="{{ $row->id }}">   {{ $row->id }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="left-15" for="selectJS">Product</label>
                        <select class="form-control selectJS" name="ctg">
                           <option value="">------</option>
                           @foreach($insuranceCtg as $ky=> $rwCtg)
                           <option value="{{ $ky }}">{{ $rwCtg->name }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="left-15" for="status">Status</label>
                        <select class="form-control selectJS" name="status">
                           <option value="">------</option>
                           <option value="0">Inactive</option>
                           <option value="1">Active</option>
                        </select>
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
<style>

</style>
@stop

@section('js')
<script>
  var oTable = $('#customerTable').DataTable({
        searching:false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("admin/customer-filter-data") }}',
            data: function (d) {
                d.id = $('select[name=id]').val();
                d.name = $('select[name=name]').val();
                d.ctg = $('select[name=ctg]').val();
                d.status = $('select[name=status]').val();
                d.searchTerm = $('input[name=searchTerm]').val();
            }
        },
        columns: [
            {data: 'id', name: 'id'},
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
    ],
       
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
        
    });


</script>
@stop