var taskapp = new Vue({
    el: '#task-app',
    data: {
      urlPrefix:urlPrefix,
      modalAction:"",
      tasks:{
        due_date:null,
        task_name:null,
        task_detail:null,
        assigned_id:null,
        assigned_on:null,
        taskid:null,
        customerid:null,
        priority:null,
        comment:null,
        status:null,
        
      },
      taskUsers:{},
      mytaskData:[],
      taskData:[],
      errors:[],
    },

    validations:{
        tasks:{
            task_name:{
                required:required,
             },
             assigned_id:{
                required:required,
             },
             due_date:{
                required:required,
             },
             priority:{
                required:required,
             },
             status:{
                required:required,
             },
        },
      
    },
    created: function(){
    
    }, 
    mounted: function(){
       
        if($('#taskTable').length>0){
            this.loadAllTasks();

        }
        if($('#mytaskTable').length>0){
            this.fetchMyTaskList();

        }
        
        this.loadTaskUser();
        this.loadDatepicker();
       
     },

    methods: {
        loadDatepicker:function(){
            self=this;
            $('#due_date').datepicker({
                format:'dd-mm-yyyy',
                todayHighlight: true,
            }).on(
                'changeDate',  function() { self.tasks.due_date = $('#due_date').val();  }
            )
        },

        assignTask:function(){
        
            this.$http.post(this.urlPrefix+'assigntask',this.tasks).then(
                function(response){
                    this.$toaster.success(response.data);
                    $('#addTask').modal('hide');
                }
            )
        
        },

        addTask:function(){
            
            if (this.$v.tasks.$invalid) {
                this.$v.tasks.$touch()
               }
              else{
                this.$http.post(this.urlPrefix+'task-list',this.tasks).then(
                    function(response){
                        this.$toaster.success(response.data);
                        $('#addTask').modal('hide');
                        $('#taskTable').DataTable().destroy();
                        this.loadAllTasks();
                 }
            )
          }
        },

        updatetasks:function(){
            if (this.$v.tasks.$invalid) {
                this.$v.tasks.$touch()
               }
              else{
                this.$http.put(this.urlPrefix+'task-list/'+ this.tasks.taskid ,this.tasks).then(
                    function(response){
                        this.$toaster.success(response.data);
                        $('#addTask').modal('hide');
                        this.loadAllTasks();
                        
                    }
                ).catch(function(response){
                    let self = this;
                    $.each(response.data.errors, function(key, value){
                        self.$toaster.error(value[0]);
                    });
                });
          }
        },

        loadTaskUser:function(){
            this.$http.get(this.urlPrefix+'fetchtaskusers').then(
                function(response){
                    this.taskUsers  = response.data;
                  
                }
            )
        },

        loadTaskHistories:function(){

        },
        
        loadTaskDetail:function(item,cutomerId){
            this.loadTaskUser();
            this.modalAction='add';
            this.tasks.task_name="";
            this.tasks.task_detail="";
            this.tasks.due_date="";
            this.tasks.assigned_id="";
            this.tasks.taskid="";
            this.tasks.priority="";
            this.tasks.status="New";
            
            if(cutomerId!=null){
                this.tasks.customerid=cutomerId;
            }
            else{
                this.tasks.customerid="";
            }
           
            if(item !== null){
                this.modalAction='edit';
                this.tasks.task_name=item.task_name;
                this.tasks.task_detail=item.task_detail;
                this.tasks.due_date=item.due_date;
                this.tasks.assigned_id=item.assigned_id;
                this.tasks.taskid=item.taskid;
                this.tasks.priority=item.priority;
                this.tasks.status=item.status;
            }
            this.$v.tasks.$reset();  
        },

        loadAllTasks:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchtasklist').then(
                function(response){
                    self.taskData  = response.data.data;
                }
            )
            self.loadDataTable();
        },

        fetchMyTaskList:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchmytasklist').then(
                function(response){
                    self.mytaskData  = response.data.data;
                }
            )
            self.loadDataTable();
        },

        loadDataTable:function(){
          setTimeout( function(){ $('#taskTable').DataTable(); },500)
          setTimeout( function(){ $('#mytaskTable').DataTable(); },500)
          
        }

     },
     
    delimiters: ["<%","%>"]
  })

 
  