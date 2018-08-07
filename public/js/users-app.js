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
 phoneRegx = regexhelpers('phoneRegx',/^\d{10,14}$/);

var app = new Vue({
    el: '#users-app',
    data: {
      urlPrefix:"/admin/",
      currentUserId:"",
      user:{
        email:null,
        password:null,
        name:null,
        usertype:'',
      },
      usersData:[],
      errors:[],

    },
    validations:{
        user:{
          email:{
              required:required,
              email:email,
          },
          password:{
            required:required
          },
          usertype:{
            required:required
          },
          name:{
            required:required
          }
        }
    },
    created: function(){
        this.currentUserId = $('#currentUserId').val();
        
    }, mounted: function(){
        if(this.currentUserId)
        this.getUserData();
        if($('#userTable').length>0){
            this.loadAllUsers();
        }
     },
    methods: {
        addNewUser: function () {
        if (this.$v.$invalid) {
            this.$v.$touch()
        }else{
            this.$http.post(this.urlPrefix+'saveuser',this.user).then(
                function(response){
                    this.$toaster.success(response.data);
                }
            ).catch(function(response){
                let self = this;
                $.each(response.data.errors, function(key, value){
                    self.$toaster.error(value[0]);
                  });
            });
        } 
     }, getUserData: function () {
        this.$http.get(this.urlPrefix+'fetchuser/'+this.currentUserId).then(function(response){
            this.user=response.data;
        });
      }, updateUser: function (event) {
            this.$http.post(this.urlPrefix+'user-form/'+this.currentUserId,this.user).then(
            function(response){
                this.$toaster.success(response.data);
            }
        )
      },onDelete:function(id){
          this.currentUserId = id;
      },deleteUser:function(){
            this.$http.post(this.urlPrefix+'deleteuser/'+this.currentUserId,this.currentUserId).then(
                function(response){
                   $('#userTable').DataTable().destroy();
                   this.loadAllUsers();
                   $('#deleteModal').modal('hide');
                   this.$toaster.success(response.data);
                }
            )
        },
        loadAllUsers:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'userdatatable').then(
                function(response){
                    self.usersData  = response.data.data;
                }
            )
            self.loadDataTable();
        },
        loadDataTable:function(){
            setTimeout( function(){ $('#userTable').DataTable(); },500)
        }

     },
     
    delimiters: ["<%","%>"]
  })

  