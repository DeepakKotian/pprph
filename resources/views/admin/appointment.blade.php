@extends('adminlte::page')

@section('title', 'Appointment')

@section('content_header')
<h1>
        Appointment
        <small>Control panel</small>
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
              <div class="form-group" :class="{ 'has-error': $v.appointment.title.$error }">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" v-model="$v.appointment.title.$model" class="form-control" placeholder="Event Title">
              </div>
              <div class="form-group" :class="{ 'has-error': $v.appointment.title.$error }">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" v-model="$v.appointment.description.$model" id="description" rows="5"></textarea>
              </div>
              <div class="form-group" :class="{ 'has-error': $v.appointment.assigned_id.$error }">
                  <label for="exampleInputEmail1">With </label>
                  <select class="form-control"  name="assigned_id" v-model="$v.appointment.assigned_id.$model">
                    <option>Please Selct</option>
                    <option v-for="usr in users" :value="usr.id"> <% usr.first_name%> <% usr.last_name%></option>
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
               <button class="btn btn-primary" v-show="action=='edit'" type="button">UPDATE</button>
               <button class="btn btn-danger" v-show="action=='edit'" type="button">DELETE</button>
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

<script>
$(function () {
  /* initialize the calendar
  -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  // var date = new Date()
  // var d    = date.getDate(),
  //     m    = date.getMonth(),
  //     y    = date.getFullYear();

  // $('#calendar').fullCalendar({
  //   header    : {
  //     left  : 'prev,next today',
  //     center: 'title',
  //     right : 'month,agendaWeek,agendaDay'
  //   },
  //   buttonText: {
  //     today: 'today',
  //     month: 'month',
  //     week : 'week',
  //     day  : 'day'
  //   },
  //   //Random default events
  //   events    : [
  //     {
  //       title          : 'All Day Event',
  //       start          : new Date(y, m, 1),
  //       end          : new Date(y, m, 4),
  //       backgroundColor: '#f56954', //red
  //       borderColor    : '#f56954' //red
  //     },
  //     {
  //       title          : 'Long Event',
  //       start          : new Date(y, m, d - 5),
  //       end            : new Date(y, m, d - 2),
  //       backgroundColor: '#f39c12', //yellow
  //       borderColor    : '#f39c12' //yellow
  //     },
  //     {
  //       title          : 'Meeting',
  //       start          : new Date(y, m, d, 10, 30),
  //       allDay         : false,
  //       backgroundColor: '#0073b7', //Blue
  //       borderColor    : '#0073b7' //Blue
  //     },
  //     {
  //       title          : 'Lunch',
  //       start          : new Date(y, m, d, 12, 0),
  //       end            : new Date(y, m, d, 14, 0),
  //       allDay         : false,
  //       backgroundColor: '#00c0ef', //Info (aqua)
  //       borderColor    : '#00c0ef' //Info (aqua)
  //     },
  //     {
  //       title          : 'Birthday Party',
  //       start          : new Date(y, m, d + 1, 19, 0),
  //       end            : new Date(y, m, d + 1, 22, 30),
  //       allDay         : false,
  //       backgroundColor: '#00a65a', //Success (green)
  //       borderColor    : '#00a65a' //Success (green)
  //     },
  //     {
  //       title          : 'Click for Google',
  //       start          : new Date(y, m, 28),
  //       end            : new Date(y, m, 29),
  //       url            : 'http://google.com/',
  //       backgroundColor: '#3c8dbc', //Primary (light-blue)
  //       borderColor    : '#3c8dbc' //Primary (light-blue)
  //     }
  //   ],
  //   editable  : true,
  //   droppable : true, // this allows things to be dropped onto the calendar !!!
  //   drop      : function (date, allDay) { // this function is called when something is dropped

  //     // retrieve the dropped element's stored Event Object
  //     var originalEventObject = $(this).data('eventObject')

  //     // we need to copy it, so that multiple events don't have a reference to the same object
  //     var copiedEventObject = $.extend({}, originalEventObject)

  //     // assign it the date that was reported
  //     copiedEventObject.start           = date
  //     copiedEventObject.allDay          = allDay
  //     copiedEventObject.backgroundColor = $(this).css('background-color')
  //     copiedEventObject.borderColor     = $(this).css('border-color')

  //     // render the event on the calendar
  //     // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
  //     $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

  //     // is the "remove after drop" checkbox checked?
  //     if ($('#drop-remove').is(':checked')) {
  //       // if so, remove the element from the "Draggable Events" list
  //       $(this).remove()
  //     }

  //   }
  // })
})
</script>
@stop