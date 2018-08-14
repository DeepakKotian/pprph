@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')   
   <h1>
Documents List
        <small>View list of documents</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      
        <li class="active">>Documents List</li>
      </ol>

@stop
@section('content')
       
<!-- /.box-header -->
<div class="box box-primary" id="documents-app">
       <!-- Breadcrumbs-->
        <div class="box-header">
              <h3 class="box-title">       View Customers List</h3>
              <div class="box-tools">
                <!-- <a class="btn btn-primary btn-md pull-right" href="{{ url('admin/user-form') }}">Add New</a> -->
            </div>
        </div>
        <!-- Breadcrumbs end -->
        <div class="box-body">
            <div class="form-group">
               <form method="POST" id="search-form" class="form-inline" role="form">
                    <div class="form-group">
                        <label for="searchTerm"></label>
                        <input type="text" class="form-control" name="searchTerm" id="searchTerm" placeholder="Quick Search">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-12">
                <div class="table table-responsive">
                    <table class="table table-bordered" id="customerTable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Document </th>
                                    <th>Customer Name</th>
                                    <th>Action</th>                           
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
            <button class="btn btn-primary"   type="button">Yes</a>
          </div>
        </div>
      </div>
</div>
<div class="modal fade" id="loadDocument" tabindex="-1" role="dialog" aria-labelledby="loadDocumentLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Document Detail
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></h5>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer">
            <!-- <button class="btn btn-primary"   type="button">OK</a> -->
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
            url: '{{ url("admin/document-filter-data") }}',
            data: function (d) {
                d.searchTerm = $('input[name=searchTerm]').val();
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'document_name', name: 'document_name'},
            {data: 'name', name: 'name'},
            {data: 'id', name: 'id'},
          
        ],
        columnDefs:[{
            targets: -1,
            data: null,
            render: function(data, type, full, meta){
                console.log();
                    if(type === 'display'){
                        data = '<p align="center"><a href="javascript:void(0)" class="btn-view"> <span class="fa fa-eye" rel="ctg_'+meta.row+'_'+meta.col+'"></span> </a>';
                        data += '</p>';
                        //<a href=""> <span class="fa fa-trash"></span> </a> 
                    }
            
               return data;
            }
       }]
    });
    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

 
     $('#customerTable tbody').on('click', '.btn-view', function (e) {
        var data = oTable.row( $(this).parents('tr') ).data();
        str = '';
        str+='<div class="form-group"> <label> Name : '+data.name+'</label> </div>';
        str+='<div class="form-group"> <label> File : </label> <a href=""> <img src="/document/'+data.name+'"> '+data.name+' </a>  </div>';
        str+='<div class="form-group">  <a href="" class="btn btn-primary"> Download </a>  </div>';
        $('#loadDocument').modal('show');
        $('#loadDocument .modal-body').html(str);
    });
    </script>
@stop