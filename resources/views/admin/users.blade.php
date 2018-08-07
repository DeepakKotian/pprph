@extends('layouts.adminapp')

@section('content')

@section('title','Dashboard') 
@section('description','Dashboard Description') 
<div class="content-wrapper" id="users-app">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <a href="">Home</a>
            </li>
            <li class="breadcrumb-item active">Users List</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <a class="btn btn-primary btn-md pull-right" href="{{ url('admin/user-form') }}">Add New</a>
            </div>
            <div class="col-12">
            <div class="table table-responsive">
                <table class="table table-bordered" id="userTable">
                        <thead>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Options</th>
                        </thead>
                        <tbody>
                            <tr v-for="row in usersData">
                                <td><% row.id %></td>
                                <td><% row.name %></td>
                                <td><% row.email %></td>
                                <td><% row.usertype %></td>
                                <td> &emsp;<a v-bind:href="'/admin/user-form/'+ row.id" title="SHOW" ><span class="fa fa-edit"></span></a>&emsp;
                                &emsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#deleteModal" v-on:click="onDelete(row.id)"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
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
</div>

@endsection
@section('script')

<script src="{{ asset('js/datatable/jquery.dataTables.js') }}"></script>

<script>
    // $(document).ready(function(){
    //     $('#userTable').DataTable({
    //         "processing": true,
    //         "serverSide": true,
    //         "searching":false,
    //         "ajax":{
    //                  "url": "/admin/userdatatable",
    //                  "dataType": "json",
    //                  "type": "POST",
    //                  "data":{ _token: "{{csrf_token()}}"},
    //                  "dataSrc" : function (response) {
    //                    return response.data;
    //                 }
    //                },
            
    //         "columns": [
    //             { "data": "id" },
    //             { "data": "name" },
    //             { "data": "email" },
    //             { "data": "usertype" },
    //             { "data": "options" }
    //         ],
    //         "columnDefs": [{
    //             "targets": 4,
    //             "data": "options",
    //             "render": function ( data, type, row, meta ) {
    //                 var str;
    //                 str= '&emsp;<a href="admin/user-form/'+data+'" title="SHOW" ><span class="fa fa-edit"></span></a>';
    //                 str+='&emsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#deleteModal" v-on:click="onDelete('+data+')"><span class="fa fa-trash"></span></a>';
    //                 return str;
    //             }
    //         }]	 
    //     });
    // })
</script>
<script src="{!! asset('js/users-app.js') !!}"></script>
@endsection
