@extends('adminlte::page')
@section('title', 'Task')
@section('content_header')   
<h1>
   Tasks Detail <small> Task Details</small>
</h1>

<ol class="breadcrumb">
   <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active"> Task Details</li>
</ol>

@stop
@section('content')
<!-- /.box-header -->
<div class="row" id="task-app" v-cloak>
<div class="col-sm-4">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"> Task Details</h3>
              <div class="box-tools">
                  
                    <a class="btn btn-warning " data-toggle="modal"  v-on:click="loadAddRemarks(initialTask)" data-target="#assignTask" > Add Remarks </a>
                    <a class="btn btn-primary " href="{{ url()->previous() }}" >Back</a>
              </div>
            </div>
            
            <input type="hidden" value="{{ $taskinitailId }}" id="tasksId">
            <!-- /.box-header -->
            <div class="box-body ">
              <table class="table table-condensed">
                <tbody>
                <tr>
                  <td><label>Date:</label></td>
                  <td>
                  <% initialTask.assigned_on %>
             
                 </td>
                 
                </tr>
                <tr>
                  <td><label >Due Date:</label></td>
                  <td>
                  <% initialTask.due_date %>
                  </td>
                </tr>

                <tr>
                  <td><label>Task Name:</label></td>
                  <td>
                  <% initialTask.task_name %>
                  </td>
                 
                </tr>
                <tr>
                  <td><label>Current status:</label></td>
                  <td>
                  <% initialTask.status %>
                  </td>
                </tr>
                <tr>
                  <td><label>Assigned On:</label></td>
                  <td>
                  
                  <% initialTask.assigned_on %>
                  </td>
                </tr>
                <tr>
                  <td><label>Assigned By:</label></td>
                  <td>
                   <% initialTask.first_name %>  <% initialTask.last_name %>

                  </td>
                </tr>

                <tr>
                  <td><label>Priority:</label></td>
                  <td>
                  <% initialTask.priority %>
                  </td>
                </tr>
                
               
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
       
          <!-- assign form -->
    <div class="modal fade" id="assignTask" tabindex="-1" role="dialog" aria-labelledby="addTaskLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title"  id="exampleModalLabel"> <span style="text-transform:capitalize"><% modalAction %></span> Remarks</h4>
                </div>
                <div class="modal-body">
                <div class="box-body">
                    <input type="hidden" class="form-control" v-model="tasks.taskid" id="exampleInputEmail1" placeholder="Enter Task Name">

                    <input type="hidden" class="form-control" v-model="tasks.task_name" id="exampleInputEmail1" placeholder="Enter Task Name">
                
                    <input type="hidden" class="form-control" v-model="tasks.task_detail" id="exampleInputEmail1" placeholder="Enter Task Name">
                 
                    <input type="hidden" class="form-control" v-model="tasks.priority" id="exampleInputEmail1" placeholder="Enter Task Name">
                   
                    <!-- <div class="form-group" :class="{ 'has-error': $v.tasks.assigned_id.$error }">
                    
                        <label for="exampleInputEmail1">Assign to </label>
                        <select class="form-control"  name="" v-model="$v.tasks.assigned_id.$model" id="">
                        <option value="">-------</option>
                        <option v-for="taskuser in taskUsers" :value="taskuser.id"> <% taskuser.first_name%> <% taskuser.last_name%></option>
                        </select>
                    </div> -->
                    <!-- <input type="hidden" class="form-control" v-model="$v.tasks..$model" id="exampleInputEmail1" placeholder="Enter Task Name"> -->
                    <div class="form-group" :class="{ 'has-error': $v.tasks.due_date.$error }">
                        <label for="exampleInputEmail1">Due Date </label>
                        <div class="input-group">
                        <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                        </div>
                        <input readonly type="text" v-model="$v.tasks.due_date.$model" class="form-control"  name="" id="due_date">
                        </div>
                    </div>
                    <div v-if="modalAction=='add'" class="form-group" :class="{ 'has-error': $v.tasks.status.$error }">
                      <label for="exampleInputEmail1">Status *</label>
                      <select class="form-control"  name="" v-model="$v.tasks.status.$model" id="">
                        <option value="New">New</option>
                        <option value="In progress">In progress</option>
                        <option value="On Hold">on Hold</option>
                        <option value="Completed">Completed</option>
                    </select>
                    </div>
                    <div class="form-group" :class="">
                    <label for="exampleInputEmail1">Remarks </label>
                    <textarea name="" id="" v-model="tasks.comment" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" v-if="modalAction=='add'" v-on:click="assignTask()" type="button">Add</a>
                    
                </div>
            </div>
        </div>
   </div>
   <!-- assign form end -->
</div>
<div class="col-sm-8">
<div class="box">
            <div class="box-header">
              <h3 class="box-title">Task History</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                 
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive ">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th>Modified By</th>
                  <th>Due Date</th>
                  <th>Status</th>
                  <th>Comment</th>
                </tr>
                <tr v-for="(rw,ky) in taskHistory">
                  <td><% rw.a_first_name + ' ' + rw.a_last_name %></td>
                  <td><% rw.due_date %></td>
                  <td><% rw.status %></td>
                  <td><% rw.comment %></td>
                </tr>
               </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
</div>
</div>

</div>
@stop

@section('js')
<script src="{!! asset('js/task-app.js') !!}"></script>
@stop