let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Vue.http.interceptors.push(function(request, next) {
        request.headers.set('X-CSRF-TOKEN', token.content)
        next()
      })
} 

var tokenDt = token.content;
Vue.use(window.vuelidate.default)
Vue.use(VToaster, {timeout: 5000})

var required     = window.validators.required,
 sameAs          = window.validators.sameAs,
 regexhelpers    = window.validators.helpers.regex,
 email           = window.validators.email,
 minLength       = window.validators.minLength,
 numeric         = window.validators.numeric,
 url             = window.validators.url,
 pwdRegx = regexhelpers('pwdRegx', /^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[$@$!%*?&])[A-Za-z\d$@$!%*?&]{6,}/),
 phoneRegx = regexhelpers('phoneRegx',/^[+]?[0-9]\d{9,}$/);

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

 
  