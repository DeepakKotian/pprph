@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
<h1>Dashboard</h1>
@stop
@section('content')
<div id="dashboard-app" v-cloak>

    <div class="row " >
    @can('manage-admin')
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ url('/admin/users') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>

    </div>
    
    @endcan
    
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
    
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $customerCount }}</h3>
                <p>Customers</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="{{ url('/admin/customers') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><% dueAppointments.length %></h3>
                <p>Appointments</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar"></i>
            </div>
            <a href="{{ url('/admin/appointment') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3><% dueTasks.length %></h3>
                <p>Due Tasks</p>
            </div>
            <div class="icon">
                <i class="fa fa-tasks"></i>
            </div>
            <a href="{{ url('/admin/task-list') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    </div>
    <div class="row ">
    <div class="col-lg-6 col-xs-12">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title text-bold">Due tasks</h3>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table table-responsive">
                <table id="dueTask" class="table table-bordered table-hover">
                <thead>
                    <tr>
                       <th>#</th>
                       <th>Assigned On</th>
                        <th>Assigned By</th>
                        @can('manage-admin')
                        <th>Assigned To</th>
                        @endcan
                        <th>Name</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(duetask, dueid) in dueTasks">
                        <td><% dueid+1 %></td>
                        <td><% duetask.assigned_on %>
                        </td>
                        <td><% duetask.first_name %> <% duetask.last_name %></td>
                        @can('manage-admin')
                        <td><% duetask.a_first_name %> <% duetask.a_last_name %></td>
                        @endcan
                        <td> <% duetask.task_name %></td>
                        <td> <% duetask.priority %></td>
                        <td> <% duetask.status %></td>
                        <td> <a type="button" v-bind:href="urlPrefix+'task-list/'+ duetask.taskid" class="btn btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
                    </tr>
                   
                </tbody>
                </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-lg-6 col-xs-12">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title text-bold">Appointments</h3>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table table-responsive">
                <table id="dueAppointments" class="table table-bordered table-hover">
                <thead>
                    <tr>
                       <th>#</th>
                       <th>Created On</th>
                       <th>Appointment at</th>
                        <th>Created By</th>
                        <th>Appointment to</th>
                      
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(dueAppintment, appintmentid) in dueAppointments">
                        <td><% appintmentid+1 %></td>
                        <td><% dueAppintment.created_on %></td>
                        <td><% dueAppintment.appointment_start_at %> - <% dueAppintment.appointment_end_at %></td>
                        <td><% dueAppintment.first_name %> <% dueAppintment.last_name %></td>
                        <td><% dueAppintment.cfirst_name %> <% dueAppintment.clast_name %></td>
                       
                        <td> <% dueAppintment.title %></td>
                      
                        <td> <a type="button" href="{{url('/admin/appointment')}}" class="btn btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
                    </tr>
                   
                </tbody>
                </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    </div>
    <div class="row">
    <div class="col-lg-6 col-xs-12">
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title text-bold">Expiring insurance</h3>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table table-responsive">
                    <table id="dueInsurance" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th class="hide">pid</th>
                        <th>Policy Nr.</th>
                        <th>Customer Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Insurance Name</th>
                            <th>Provider Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(dueInsurance, insureindex) in dueInsurances">
                            <td><% insureindex+1 %></td>
                            <td class="hide"><% dueInsurance.pid %></td>
                            <td > <% dueInsurance.policy_number %></td>
                            <td><% dueInsurance.cfirst_name %> <% dueInsurance.clast_name %></td>
                            <td> <% dueInsurance.pstart_date %></td>
                            <td> <% dueInsurance.pend_date %></td>
                            <td><% dueInsurance.ctgName%></td>
                            <td><% dueInsurance.providerName%></td>
                            <td> <a type="button"  v-bind:href="urlPrefix+'customer-form/'+ dueInsurance.cstId" class="btn btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
                        </tr>
                    
                    </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
 
 </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('js')
<script src="{!! asset('js/dashboard-app.js') !!}"></script>
@stop