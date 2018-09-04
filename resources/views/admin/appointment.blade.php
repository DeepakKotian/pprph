@extends('adminlte::page')

@section('title', 'Appointment')

@section('content_header')
<h1>
        Appointment
        <small>Appointment list</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Appointment</li>
      </ol>
@stop

@section('content')
<div class="row" id="calender-app" v-cloak>
        <div class="col-md-3">
          <form action="" method="post">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create Event</h3>
            </div>
            <div class="box-body">

             <input type="hidden" id="user_id" value="{{Auth::user()->id}}" v-model="user_id" >
              <div class="form-group" :class="{ 'has-error': $v.appointment.title.$error }">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" v-model="$v.appointment.title.$model" class="form-control" placeholder="Event Title">
              </div>
              <div class="form-group" :class="{ 'has-error': $v.appointment.title.$error }">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" v-model="$v.appointment.description.$model" id="description" rows="5"></textarea>
              </div>

              <div class="form-group" class="radioroggle">
                <label class="radio-inline">
                  <input type="radio" name="optradio" value="1"  :checked="showAssign==false">Own
                </label>
                <label class="radio-inline">
                  <input type="radio" name="optradio" value="2" :checked="showAssign">Other
                </label>
              </div>

              <div class="form-group" v-if="showAssign" :class="{ 'has-error': $v.appointment.assigned_id.$error }">
                  <label for="exampleInputEmail1">Assign To </label>
                  <select class="form-control"  name="assigned_id" v-model="$v.appointment.assigned_id.$model">
                    <option value="" >Please Selct</option>
                    <option v-for="usr in users" :value="usr.id"> <% usr.first_name%> <% usr.last_name%></option>
                  </select>
              </div>

             <div class="form-group" :class="{ 'has-error': $v.appointment.customer_id.$error }"  >
                  <label for="exampleInputEmail1">Customer </label>
                  <select class="form-control"  name="assigned_id" v-model="$v.appointment.customer_id.$model" >
                    <option value="">Please Selct</option>
                    <option v-for="cst in customers" :value="cst.id"> <% cst.first_name%> <% cst.last_name%></option>
                  </select>
              </div>

              <div class="form-group" :class="{ 'has-error': $v.appointment.start_date.$error }">
                <label for="start_date">Start Date &amp; Time </label>
                <div class="input-group">
                  <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                  <input readonly type="text" class="form-control"  name="start_date" id="start_date" v-model="$v.appointment.start_date.$model">
                  <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                  </div>
                  <input readonly type="text" class="form-control"  name="start_time" id="start_time" v-model="appointment.start_time">
                </div>
              </div>
              <div class="form-group" :class="{ 'has-error': $v.appointment.end_date.$error }">
                <label for="end_date">End Date &amp; Time </label>
                <div class="input-group">
                  <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                  </div>
                  <input readonly type="text" class="form-control" v-model="$v.appointment.end_date.$model"  name="" id="end_date">
              
                <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                  </div>
                <input readonly type="text" class="form-control"  name="end_time" id="end_time" v-model="appointment.end_time">
                </div>
              </div>
            </div>
            <div class="box-footer text-center">
               <button class="btn btn-info" type="button" v-on:click="appointment=[]; action=''">CLEAR</button>
               <button class="btn btn-primary" v-show="action!='edit'" type="button" v-on:click="addAppointment">ADD</button>
               <button class="btn btn-primary" v-show="action=='edit'" type="button" v-on:click="updateAppointment">UPDATE</button>
               <button class="btn btn-danger" v-show="action=='edit'" data-toggle="modal" data-target="#deleteModal"  v-on:click="onDelete(appointment.id)" type="button">DELETE</button>
            </div>
          </div>
          </form>
        </div>

        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="exampleModalLabel">Delete?</h4>
                        </div>

                        <div class="modal-body">Are you sure you want to delete ?</div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary"   v-on:click="deleteAppintment(appintmentId)" type="button">Yes</a>
                        </div>

                </div>

   </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/calendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/calendar/fullcalendar.print.min.css') }}" media="print">
@stop

@section('js')
<script src="{{ asset('vendor/adminlte/calendar/moment.js') }}"></script>
<script src="{{ asset('vendor/adminlte/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/calender-app.js') }}"></script>


@stop