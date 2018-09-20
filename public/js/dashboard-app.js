var calenderapp = new Vue({
    el: '#dashboard-app',
    data: {
      urlPrefix:urlPrefix,
      dueTasks:[],
      dueAppointments:[],
      dueInsurances:[],
    },
  
    created: function(){
        this.loadAllDueTask();
        this.loadAllDueAppointments();
        this.loadAllDueInsurance();
    }, 
    mounted: function(){
   
    },

    methods: {
        loadAllDueTask:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetch-due-task').then(
                function(response){
                  $('#dueTask').DataTable().destroy();
                  self.dueTasks =  response.data.data;
              
                }
            )
            self.loadDataTable();
        },

        loadAllDueAppointments:function(){
          let self = this;
          this.$http.post(this.urlPrefix+'fetch-due-appointments').then(
              function(response){
                $('#dueAppointments').DataTable().destroy();
                self.dueAppointments=  response.data.data;
             
            
              }
          )
          self.loadDataTable();
      },

      loadAllDueInsurance:function(){
        let self = this;
        this.$http.post(this.urlPrefix+'fetch-due-insurance').then(
            function(response){
              $('#dueInsurance').DataTable().destroy();
              self.dueInsurances=  response.data.data;
          
            }
        )
        self.loadDataTable();
    },

        loadDataTable:function(){  
          setTimeout( function(){ $('#dueTask').DataTable({lengthChange:false, pageLength:3, "searching": false,destroy: true, "order": [[1, "desc" ]]}); },1000)
          setTimeout( function(){ $('#dueAppointments').DataTable({lengthChange:false, pageLength:3, "searching": false,destroy: true, "order": [[1, "desc" ]]}); },1000)
          setTimeout( function(){ $('#dueInsurance').DataTable({ columnDefs: [ {
            targets: [ 2 ],
            orderData: [ 1 ]
        }],lengthChange:false, pageLength:3, "searching": false,destroy: true, "order": [[1, "asc" ]]}); },1000)

        },
     
     },
    
     
    delimiters: ["<%","%>"]
  })

 
  