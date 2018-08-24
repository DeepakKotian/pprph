@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')   
<h1>
   Tasks Detail <small> Tasks Detail</small>
</h1>

<ol class="breadcrumb">
   <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active"> Tasks Detail</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="row" id="task-app">
<div class="col-sm-4">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"> Tasks Detail</h3>
              <div class="box-tools">
              <a class="btn btn-primary " href="{{ url('admin/mytask-list')}}" >Back</a>
                    <a class="btn btn-warning " data-toggle="modal" v-on:click="loadTaskDetail({{ $task }})" data-target="#assignTask" >Assign</a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body ">
              <table class="table table-condensed">
                <tbody>
                <tr>
                  <td><label>Date:</label></td>
                  <td>
                  {{ $task->assigned_on }}
                 </td>
                 
                </tr>
                <tr>
                  <td><label>Due Date:</label></td>
                  <td>
                  {{ $task->due_date }}
                  </td>
                </tr>

                <tr>
                  <td><label>Task Name:</label></td>
                  <td>
                  {{ $task->task_name }}
                  </td>
                 
                </tr>
                <tr>
                  <td><label>Current status:</label></td>
                  <td>
                  {{ ucfirst($task->status) }}
                  </td>
                </tr>
                <tr>
                  <td><label>Assigned On:</label></td>
                  <td>
                  {{ $task->assigned_on }}
                  </td>
                </tr>
                <tr>
                  <td><label>Assigned By:</label></td>
                  <td>
                  {{ $task->first_name }} {{ $task->last_name }}
                  </td>
                </tr>
                <tr>
                  <td><label>Priority:</label></td>
                  <td>
                  {{ ucfirst($task->priority) }}
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
                    <h4 class="modal-title"  id="exampleModalLabel"> <span style="text-transform:capitalize"><% modalAction %></span> Assign</h4>
                
                </div>
                <div class="modal-body">
                <div class="box-body">
                 
                    <input type="hidden" class="form-control" v-model="tasks.task_name" id="exampleInputEmail1" placeholder="Enter Task Name">
                
                    <input type="hidden" class="form-control" v-model="tasks.task_detail" id="exampleInputEmail1" placeholder="Enter Task Name">
                 
                    <input type="hidden" class="form-control" v-model="tasks.priority" id="exampleInputEmail1" placeholder="Enter Task Name">
                   
                    <div class="form-group" :class="{ 'has-error': $v.tasks.assigned_id.$error }">
                    
                        <label for="exampleInputEmail1">Assign to </label>
                        
                        <select class="form-control"  name="" v-model="$v.tasks.assigned_id.$model" id="">
                        <option value="">-------</option>
                        <option v-for="taskuser in taskUsers" :value="taskuser.id"> <% taskuser.first_name%> <% taskuser.last_name%></option>
                        </select>
                    </div>

                    <div class="form-group" :class="{ 'has-error': $v.tasks.due_date.$error }">
                        <label for="exampleInputEmail1">Due Date </label>
                        <div class="input-group">
                        <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                        </div>
                        <input readonly type="text" v-model="$v.tasks.due_date.$model" class="form-control"  name="" id="due_date">
                        </div>
                    </div>
                    <div class="form-group" :class="">
                    <label for="exampleInputEmail1">Comment </label>
                    <textarea name="" id="" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" v-if="modalAction=='edit'" v-on:click="updatetasks()" type="button">Save</a>
                    <button class="btn btn-primary" v-if="modalAction=='add'" v-on:click="addTask()" type="button">Save</a>
                </div>
            </div>
        </div>
   </div>
   <!-- assign form end -->
</div>
</div>
</div>
@stop

@section('js')
<script src="{!! asset('js/task-app.js') !!}"></script>
@stop