

var app = new Vue({
    el: '#task-app',
    data: {
      urlPrefix:urlPrefix,
      modalAction:"",
      tasks:{
        due_date:null,
        task_name:null,
        task_detail:null,
        assigned_id:null,
      },
      taskUsers:{},
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
                    console.log(this.taskUsers);
                    
                }
            )
        },
        loadTaskDetail:function(item){
            this.loadTaskUser();
            this.modalAction='add';
            this.tasks.name="";
            
            if(item !== null){
                this.modalAction='edit';
                this.tasks.name=item.name;
                this.tasks.id=item.id;
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

        loadDataTable:function(){
          setTimeout( function(){ $('#taskTable').DataTable(); },500)
        
        }

     },
     
    delimiters: ["<%","%>"]
  })

 
  