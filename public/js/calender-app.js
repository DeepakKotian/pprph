var calenderapp = new Vue({
    el: '#calender-app',
    data: {
      urlPrefix:urlPrefix,
      action:"",
      appointment:{
        start_date:'',
        end_date:'',
        assigned_id:'',
        title:'',
        description:'',
        start_time:'',
        end_time:'',
        customer_id:'',
      },
      user_id:null,
      customers:{},
      showAssign:false,
      users:{
      },
      events:[],
      date:'',
      d : '',
      m  : '',
      y : '',
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
          },
          customer_id:{
            required:required,
          }
        }
    },
    created: function(){
        this.user_id = $('#user_id').val();
     
        
    }, 
    mounted: function(){
        this.date = new Date();
        this.d = this.date.getDate();
        this.m  = this.date.getMonth();
        this.y = this.date.getFullYear();
        this.loadCustomers();
        this.loadUsers();
        this.loadAppointments();
        let self = this;
        $('#start_time').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false,
            defaultTime: '0'
        }).on('changeTime.timepicker',function(selected){
            self.appointment.start_time = $('#start_time').val(); 
        });
        $('#end_time').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false,
            defaultTime: '0',
        }).on('changeTime.timepicker',function(selected){
            self.appointment.end_time = $('#end_time').val(); 
        });
        $('#end_date').datepicker({
            format:'dd-mm-yyyy',
            todayHighlight: true,
            startDate:'0',
        }).on(
            'changeDate',  function(selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#start_date').datepicker('setEndDate', maxDate);
            self.appointment.end_date = $('#end_date').val(); 
        })
        $('#start_date').datepicker({
            format:'dd-mm-yyyy',
            todayHighlight: true,
            startDate:'0',
        }).on(
            'changeDate',  function(selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#end_date').datepicker('setStartDate', minDate);
                self.appointment.start_date =  $('#start_date').val(); 
        })

        $('input:radio').click(function() {
            if ($(this).val() === '1') {
             self.showAssign=false;
            } else if ($(this).val() === '2') {
                self.showAssign=true;
            } 
        });
 
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
      loadCustomers:function(){
        this.$http.get(this.urlPrefix+'fetchcustomersforappointment').then(
            function(response){
                this.customers  = response.data;
               
            }
        )
        
      },
      loadUsers:function(){
        this.$http.get(this.urlPrefix+'fetchtaskusers').then(
            function(response){
                this.users  = response.data;
                this.filteruser(this.users);
            }
        )
      },

      filteruser:function(userarray){
        let self=this;
        userarray.forEach(function (item, index) {
            if (item.id == self.user_id) {
                self.users.splice(index,self.user_id);
            }
        });
      },

      loadCalender:function(){ 
    
            self = this;
            $('#calendar').fullCalendar({
               
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
                    self.showAssign=false;
                    if(self.user_id !=self.appointment.assigned_id){
                        self.showAssign=true;
                    }
                    self.appointment.customer_id = calEvent.customer_id;
                    self.appointment.start_date = moment(calEvent.start).format('DD-MM-YYYY');
                    self.appointment.end_date = moment(calEvent.end).format('DD-MM-YYYY');
                    self.appointment.start_time = moment(calEvent.start).format('HH')+':'+ moment(calEvent.start).format('mm');
                    self.appointment.end_time =  moment(calEvent.end).format('HH')+':'+ moment(calEvent.end).format('mm');
                    self.appointment.id =  calEvent.id;
                    self.action = 'edit';
                 },
                 timeFormat: 'H(:mm)',
                 events    : self.events,
                 selectable: true,
                 select: function (start, end, jsEvent, view) {
                     if (view.name == 'agendaDay') {
                        self.appointment.start_date = moment(start).format('DD-MM-YYYY');
                        self.appointment.end_date = moment(end).format('DD-MM-YYYY');
                        self.appointment.start_time = moment(start).format('HH')+':'+ moment(start).format('mm');
                        self.appointment.end_time =  moment(end).format('HH')+':'+ moment(end).format('mm');
                     }
         
                 },
                eventRender: function(eventObj, $el) {
                    
                    var t = moment(eventObj.start).format('HH') + ":" + moment(eventObj.start).format('mm') + " - " + moment(eventObj.end).format('HH') + ":" + moment(eventObj.end).format('mm')
                    eventObj.clast_name = eventObj.clast_name!=null?eventObj.clast_name:'';
                    $el.popover({
                      title: eventObj.title +' Time- ' +t + ', With : ' + eventObj.cfirst_name+' '+ eventObj.clast_name,
                      content: eventObj.description,
                      trigger: 'hover',
                      placement: 'top',
                      container: 'body'
                    });
                  },
                
                
                dayClick: function (date, jsEvent, view) {
                     
                        $('#calendar').fullCalendar('gotoDate', date);
                        $('#calendar').fullCalendar('changeView', 'agendaDay');
                   
                },
              

                header: {
                    left: 'title',
                    center: '',
                    right: 'month,agendaWeek,agendaDay, today, prev,next,prevYear,nextYear',
                },
            })
         
            $('#calendar').fullCalendar( 'removeEventSource', self.events )
            $('#calendar').fullCalendar( 'addEventSource', self.events )
            
        },

        addAppointment:function(){
            if(this.showAssign==false){
               this.appointment.assigned_id = this.user_id;
             }
           
            if(this.$v.appointment.$invalid){
                this.$v.appointment.$touch();
            }else{
                this.$http.post(this.urlPrefix+'add-appointment',this.appointment).then( 
                function(response){
                    this.events = response.data;
                  
                    this.loadCalender();
                   
                    this.$toaster.success('Added Successfully');
                   
                }).catch(function(response){
                    $(document).find('body .v-toaster .v-toast-error').remove();
                    this.$toaster.error(response.data);
                });
            }
        },
        updateAppointment:function(){
           
            if(this.showAssign==false){
                this.appointment.assigned_id = this.user_id;
              }
         
            if(this.$v.appointment.$invalid){
                this.$v.appointment.$touch();
            }else{
                $('#calendar').fullCalendar('removeEvents', this.appointment.id); 
                this.$http.post(this.urlPrefix+'update-appointment',this.appointment).then( 
                    function(response){
                    
                        this.events = response.data;
                      
                        this.$v.appointment.$reset();
                        this.$toaster.success('Updated Successfully');
                        
                         this.loadCalender();
                      
                    }).catch(function(response){
                        this.$toaster.error(response.data);
                    });
            }
        },
        onDelete:function(){
         
          appintmentId=this.appointment.id;
        
         },

         deleteAppintment:function(appointmentId){
            $('#calendar').fullCalendar('removeEvents', appointmentId); 
               this.$http.post(this.urlPrefix+'deleteappointment',{appointmentId:appointmentId}).then(
                   function(response){
                    
                      $('#deleteModal').modal('hide');
                      this.$toaster.success('Deleted Successfully');
                       this.resetForm();
                       this.$v.appointment.$reset();
                   }
               )
           },
           resetForm:function() {
            var self = this; 
       
            Object.keys(this.appointment).forEach(function(key,index) {
              self.appointment[key] = '';
              self.action='';
            });
          },
     },
    
     
    delimiters: ["<%","%>"]
  })

 
  