@extends('adminlte::page')
@section('title', 'tasks')
@section('content_header')   
<h1>
   Tasks List <small>View list of tasks</small>
</h1>

<ol class="breadcrumb">
   <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Tasks List</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="row">
<div class="col-sm-6">
<div class="box box-primary" id="task-app" v-cloak>
   <!-- Breadcrumbs-->
   <div class="box-header">
      <h3 class="box-title">View Tasks List </h3>
      <div class="box-tools">
         <a class="btn btn-primary btn-md pull-right" data-toggle="modal" v-on:click="loadTaskDetail(null)" data-target="#addTask" >Add New</a>
      </div>
   </div>

   <div class="box-body">
      <div class="col-12">
         <div class="table table-responsive">
            <table class="table table-bordered" id="taskTable">
               <thead>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Description</th>
                  
                  <th>Options</th>
               </thead>
               <tbody>
                  <tr v-for="row in taskData">
                     <td><% row.taskid %></td>
                     <td><% row.task_name %> </td>
                     <td><% row.task_detail %> </td>
                     <td>
                        <a type="button" data-toggle="modal"  data-target="#addTask"  v-on:click="loadTaskDetail(row)" class="btn btn-default"><i class="fa fa-edit"></i></a>
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
         <div class="modal-body">Are you sure you want to delete <b>   </b> ?</div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary"   v-on:click="deleteUser(currentUserId)" type="button">Yes</a>
         </div>
      </div>
   </div>
</div>
 <!-- Add insurance -->
 @include('admin.taskmodal')
</div>
</div>
</div>
@stop

@section('js')
<script src="{!! asset('js/task-app.js') !!}"></script>
@stop