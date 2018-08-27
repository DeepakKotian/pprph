var calenderapp = new Vue({
    el: '#calender-app',
    data: {
      urlPrefix:urlPrefix,
      action:"",
      appointment:{
        start_date:null,
        end_date:null,
        assigned_id:'',
        title:null,
        description:null,
        start_time:null,
        end_time:null,
      },
      users:{

      },
      events:[],
      date:null,
      d : null,
      m  : null,
      y : null,
    },
    validations:{
        appointment:{
          start_date:{
            required:required,
          },
          end_date:{
            required:required,
          },
          assigned_id:{
            required:required,
          },
          title:{
            required:required,
          },
          description:{
            required:required,
          }
        }
    },
    created: function(){
    
    }, 
    mounted: function(){
        this.date = new Date();
        this.d = this.date.getDate();
        this.m  = this.date.getMonth();
        this.y = this.date.getFullYear();
        this.loadUsers();
        this.loadAppointments();
        let self = this;
        $('#start_time').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false,
            defaultTime: false
        }).on('changeTime.timepicker',function(selected){
            self.appointment.start_time = $('#start_time').val(); 
        });
        $('#end_time').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false,
            defaultTime: false,
        }).on('changeTime.timepicker',function(selected){
            self.appointment.end_time = $('#end_time').val(); 
        });
        $('#end_date').datepicker({
            format:'dd-mm-yyyy',
            todayHighlight: true
        }).on(
            'changeDate',  function(selected) { self.appointment.end_date = $('#end_date').val(); 
            var maxDate = new Date(selected.date.valueOf());
            $('#start_date').datepicker('setEndDate', maxDate);
        })
        $('#start_date').datepicker({
            format:'dd-mm-yyyy',
            todayHighlight: true,
        }).on(
            'changeDate',  function(selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#end_date').datepicker('setStartDate', minDate);
                self.appointment.start_date =  $('#start_date').val(); 
        })
     },

    methods: {
      loadAppointments:function(){
        this.$http.get(this.urlPrefix+'fetchappointments').then(
            function(response){
             this.events = response.data;
             this.loadCalender();  
            }
        )
      },
      loadUsers:function(){
        this.$http.get(this.urlPrefix+'fetchtaskusers').then(
            function(response){
                this.users  = response.data;
            }
        )
      },

      loadCalender:function(){ 
            console.log('dfdf');
            self = this;
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
                eventClick: function(calEvent, jsEvent, view) {
                    self.appointment.title = calEvent.title;
                    self.appointment.description = calEvent.description;
                    self.appointment.assigned_id = calEvent.assigned_id;
                    self.appointment.start_date = moment(calEvent.start).format('d-m-Y');
                    self.appointment.end_date = moment(calEvent.end).format('d-m-Y');
                    self.appointment.start_time = moment(calEvent.start).format('HH')+':'+ moment(calEvent.start).format('mm');
                    self.appointment.end_time =  moment(calEvent.end).format('HH')+':'+ moment(calEvent.end).format('mm');
                    self.action = 'edit';
                 },
                events    : self.events,
                eventRender: function(eventObj, $el) {
                    var t = moment(eventObj.start).format('HH') + ":" + moment(eventObj.start).format('mm') + " - " + moment(eventObj.end).format('HH') + ":" + moment(eventObj.end).format('mm')
                    $el.popover({
                      title: eventObj.title +' Time- ' +t + ', With:' + eventObj.first_name+' '+ eventObj.last_name,
                      content: eventObj.description,
                      trigger: 'hover',
                      placement: 'top',
                      container: 'body'
                    });
                  },
            })
            $('#calendar').fullCalendar( 'removeEventSource', self.events )
            $('#calendar').fullCalendar( 'addEventSource', self.events )
        },
        addAppointment:function(){
            if(this.$v.appointment.$invalid){
                this.$v.appointment.$touch();
            }else{
                this.$http.post(this.urlPrefix+'add-appointment',this.appointment).then( 
                function(response){
                    this.events = response.data;
                    this.loadCalender();
                    this.appointment = [];
                    this.$toaster.success('Added Successfully');
                }).catch(function(response){
                    this.$toaster.error(response.data);
                });
            }
        
        },
     },
     
    delimiters: ["<%","%>"]
  })

 
  