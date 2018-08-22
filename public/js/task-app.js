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
        taskid:null,
        customerid:null,
      },
      taskUsers:{},
      mytaskData:[],
      taskData:[],
      errors:[],

    },

    validations:{
        tasks:{
            name:{
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
            }).on(
                'changeDate',  function() { self.tasks.due_date = $('#due_date').val();  }
            )
        },

        addTask:function(){
            this.$http.post(this.urlPrefix+'task-list',this.tasks).then(
                function(response){
                    this.$toaster.success(response.data);
                    $('#taskTable').DataTable().destroy();
                    this.loadAllTasks();
                }
            )
        },

        loadTaskUser:function(){
            this.$http.get(this.urlPrefix+'fetchtaskusers').then(
                function(response){
                    this.taskUsers  = response.data;
                  
                }
            )
        },
        loadTaskDetail:function(item,cutomerId){
            console.log(cutomerId);
            
            this.loadTaskUser();
            this.modalAction='add';
            this.tasks.task_name="";
            this.tasks.task_detail="";
            this.tasks.due_date="";
            this.tasks.assigned_id="";
            this.tasks.taskid="";
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

 
  