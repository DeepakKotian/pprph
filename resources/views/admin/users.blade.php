@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')   
<h1>
   Users List <small>View list of Users</small>
</h1>

<ol class="breadcrumb">
   <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Users List</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="box box-primary" id="users-app">
   <!-- Breadcrumbs-->
   <div class="box-header">
      <h3 class="box-title">View Users List </h3>
      <div class="box-tools">
         <a class="btn btn-primary btn-md pull-right" href="{{ url('admin/user-form') }}">Add New</a>
      </div>
   </div>
   <div class="box-body">
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
                     <td><% row.first_name %> <% row.last_name %></td>
                     <td><% row.email %></td>
                     <td><% row.role | role-type %>  </td>
                     <td>
                        <a type="button"  v-bind:href="'/admin/user-form/'+ row.id" class="btn btn-default"><i class="fa fa-edit"></i></a>
                        <a type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-default" v-on:click="onDelete(row)"><i class="fa fa-trash"></i></a>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

<!-- Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Delete?</h4>
                </div>

                <div class="modal-body">Are you sure you want to delete <b> <% user.first_name %> <% user.last_name %> </b> ?</div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary"   v-on:click="deleteUser(currentUserId)" type="button">Yes</a>
                </div>

        </div>

   </div>
</div>
</div>
@stop

@section('js')
<script src="{!! asset('js/users-app.js') !!}"></script>
@stop