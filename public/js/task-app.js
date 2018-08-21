

var app = new Vue({
    el: '#task-app',
    data: {
      urlPrefix:urlPrefix,
      modalAction:"",
      tasks:{
       name:null,
       type:'category',
      },
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
       
     },

    methods: {
        loadTaskDetail:function(item){
            this.modalAction='add';
            this.tasks.name="";
            if(item !== null){
                this.modalAction='edit';
                this.tasks.name=item.name;
                this.tasks.id=item.id;
            }
            this.$v.insurance.$reset();  
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

 
  