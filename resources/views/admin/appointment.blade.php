@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>
        Appointment
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Appointment</li>
      </ol>
@stop

@section('content')
<div class="row">
        <div class="col-md-3">
          
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Create Event</h3>
            </div>
            <div class="box-body">
              <!-- <div class="btn-group" style="width: 100%; margin-bottom: 10px;">

                <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul>
              </div> -->
              <!-- /btn-group -->
              <div class="input-group">
                <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                <div class="input-group-btn">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                </div>
                <!-- /btn-group -->
              </div>
              <!-- /input-group -->
            </div>
          </div>
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
<script>
$(function () {
  /* initialize the calendar
  -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  var date = new Date()
  var d    = date.getDate(),
      m    = date.getMonth(),
      y    = date.getFullYear();

  $('#calendar').fullCalendar({
    header    : {
      left  : 'prev,next today',
      center: 'title',
      right : 'month,agendaWeek,agendaDay'
    },
    buttonText: {
      today: 'today',
      month: 'month',
      week : 'week',
      day  : 'day'
    },
    //Random default events
    events    : [
      {
        title          : 'All Day Event',
        start          : new Date(y, m, 1),
        end          : new Date(y, m, 4),
        backgroundColor: '#f56954', //red
        borderColor    : '#f56954' //red
      },
      {
        title          : 'Long Event',
        start          : new Date(y, m, d - 5),
        end            : new Date(y, m, d - 2),
        backgroundColor: '#f39c12', //yellow
        borderColor    : '#f39c12' //yellow
      },
      {
        title          : 'Meeting',
        start          : new Date(y, m, d, 10, 30),
        allDay         : false,
        backgroundColor: '#0073b7', //Blue
        borderColor    : '#0073b7' //Blue
      },
      {
        title          : 'Lunch',
        start          : new Date(y, m, d, 12, 0),
        end            : new Date(y, m, d, 14, 0),
        allDay         : false,
        backgroundColor: '#00c0ef', //Info (aqua)
        borderColor    : '#00c0ef' //Info (aqua)
      },
      {
        title          : 'Birthday Party',
        start          : new Date(y, m, d + 1, 19, 0),
        end            : new Date(y, m, d + 1, 22, 30),
        allDay         : false,
        backgroundColor: '#00a65a', //Success (green)
        borderColor    : '#00a65a' //Success (green)
      },
      {
        title          : 'Click for Google',
        start          : new Date(y, m, 28),
        end            : new Date(y, m, 29),
        url            : 'http://google.com/',
        backgroundColor: '#3c8dbc', //Primary (light-blue)
        borderColor    : '#3c8dbc' //Primary (light-blue)
      }
    ],
    editable  : true,
    droppable : true, // this allows things to be dropped onto the calendar !!!
    drop      : function (date, allDay) { // this function is called when something is dropped

      // retrieve the dropped element's stored Event Object
      var originalEventObject = $(this).data('eventObject')

      // we need to copy it, so that multiple events don't have a reference to the same object
      var copiedEventObject = $.extend({}, originalEventObject)

      // assign it the date that was reported
      copiedEventObject.start           = date
      copiedEventObject.allDay          = allDay
      copiedEventObject.backgroundColor = $(this).css('background-color')
      copiedEventObject.borderColor     = $(this).css('border-color')

      // render the event on the calendar
      // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
      $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

      // is the "remove after drop" checkbox checked?
      if ($('#drop-remove').is(':checked')) {
        // if so, remove the element from the "Draggable Events" list
        $(this).remove()
      }

    }
  })
})
</script>
@stop