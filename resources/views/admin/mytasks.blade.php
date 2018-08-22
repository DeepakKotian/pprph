@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')   
<h1>
   Tasks List <small>View list of tasks</small>
</h1>

<ol class="breadcrumb">
   <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Tasks List</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="row">
<div class="col-sm-6">
<div class="box box-primary" id="task-app">
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
            <table class="table table-bordered" id="mytaskTable">
               <thead>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Assigned By</th>
                  <th>Status</th>
                  <th>Due Date</th>
                  <th>Options</th>
               </thead>
               <tbody>
                  <tr v-for="row in mytaskData">
                     <td><% row.taskid %></td>
                     <td><% row.task_name %> </td>
                     <td><% row.task_detail %> </td>
                     <td><% row.first_name %> <% row.last_name %> </td>
                     <td><% row.status %> </td>
                     <td><% row.due_date %> </td>
                     <td>
                        <a type="button" data-toggle="modal"  data-target="#addTask"  v-on:click="loadTaskDetail(row)" class="btn btn-default"><i class="fa fa-edit"></i></a>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   @include('admin.taskmodal')
</div>
</div>
</div>
@stop

@section('js')
<script src="{!! asset('js/task-app.js') !!}"></script>
@stop