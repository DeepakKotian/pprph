

var app = new Vue({
    el: '#users-app',
    data: {
      urlPrefix:urlPrefix,
      currentUserId:"",
      user:{
        email:null,
        first_name:null,
        last_name:null,
        role:'',
        phone:'',
        password:null,
        photo:'userdefault.jpg',
      },
      profile:{ },
      usersData:[],
      errors:[],

    },
    validations:{
        user:{
          email:{
              required:required,
              email:email,
          },
          role:{
            required:required,
          },
          first_name:{
            required:required,
          },
          password:{
            required:required,
          },
          phone:{
          
            phoneRegx:phoneRegx,
        }
        },
        profile:{
            email:{
                required:required,
                email:email,
            },
            role:{
              required:required,
            },
            first_name:{
              required:required,
            },
         
            phone:{
 
                phoneRegx:phoneRegx,
            }
          }
    },
    created: function(){
        this.currentUserId = $('#currentUserId').val();
    }, 
    mounted: function(){
        this.userData();
        if(this.currentUserId)
        this.getUserData();

        if($('#userTable').length>0){
           
            this.loadAllUsers();
        }
     },

    methods: {
        addNewUser: function () {
          console.log(this.$v.user);
          if (this.$v.user.$invalid) {
            this.$v.$touch()
        }else{
            this.user.photo= $('#user_photo')[0].files[0];
            let formData= new FormData();
            formData.append('first_name',this.user.first_name);
            formData.append('last_name',this.user.last_name ? this.user.last_name:"");
            formData.append('email',this.user.email);
            formData.append('phone',this.user.phone ? this.user.phone:"" );
            if(this.user.photo===undefined){
                this.user.photo = 'userdefault.jpg';  
            }
            formData.append('photo',this.user.photo);
            formData.append('role',this.user.role);
            formData.append('password', this.user.password);
            this.$http.post(this.urlPrefix+'saveuser',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(
                function(response){
                    this.$toaster.success(response.data);
		    let self = this;
		    setTimeout(function(){ window.location.href = self.urlPrefix+'users' },2000);
                    
                }
            ).catch(function(response){
                let self = this;
                $.each(response.data.errors, function(key, value){
                    self.$toaster.error(value[0]);
                  });
            });
        } 
     }, 
     getUserData: function () {
        this.$http.get(this.urlPrefix+'fetchuser/'+this.currentUserId).then(function(response){
        this.user=response.data;
       
        });
      }, 

      userData: function () {
        this.$http.get(this.urlPrefix+'userdata').then(function(response){
        this.profile=response.data;
       
        });
      }, 

      updateUser: function (event) {
      

        if($('#user_photo')[0].files[0])
        {
            this.user.photo= $('#user_photo')[0].files[0];
        }
       
        let formData= new FormData();
        formData.append('first_name',this.user.first_name);
        formData.append('last_name',this.user.last_name?this.user.last_name:"");
        formData.append('email',this.user.email);
        formData.append('phone',this.user.phone?this.user.phone :"");
        formData.append('photo',this.user.photo);
        formData.append('role',this.user.role);
        if (this.$v.user.$invalid) {
            this.$v.$touch()
        }
        else{
            this.$http.post(this.urlPrefix+'user-form/'+this.currentUserId,formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                }).then(
                function(response){
                    this.$toaster.success(response.data);
                    setTimeout(function(){
                        window.location.href = this.urlPrefix+'users'
                    },1000)
                    
                }
                ).catch(function(response){
                    this.$toaster.error(response.data);
                });
        }
      },

      updateProfile: function (event) {
        if($('#user_photo')[0].files[0])
        {
            this.profile.photo= $('#user_photo')[0].files[0];
        }
       
        let formData= new FormData();
        formData.append('first_name',this.profile.first_name);
        formData.append('last_name',this.profile.last_name ?this.profile.last_name:"");
        formData.append('email',this.profile.email);
        formData.append('phone',this.profile.phone?this.profile.phone:"" );
        formData.append('photo',this.profile.photo);
        formData.append('role',this.profile.role);
     
        if (this.$v.profile.$invalid) {
            this.$v.profile.$touch()
        }
        else{
            this.$http.post(this.urlPrefix+'updateprofile',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(
            function(response){
                this.$toaster.success(response.data);
            }
            ).catch(function(response){
                    this.$toaster.error(response.data);
            });
       }
      },
      
      onDelete:function(data){
        
         this.currentUserId = data.id;
         this.user.first_name = data.first_name;
         this.user.last_name = data.last_name;
      },
      
      deleteUser:function(userId){
      
            this.$http.post(this.urlPrefix+'deleteuser',{userId:userId}).then(
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
                    $('#userTable').DataTable().destroy();
                    self.usersData  = response.data.data;
                   
                }
            )
            self.loadDataTable();
        },

        loadDataTable:function(){
        
            setTimeout( function(){ $('#userTable').DataTable({"order": [[0, "desc" ]],}); },1000)
        }

     },
     
    delimiters: ["<%","%>"]
  })

  Vue.filter('role-type', function (value) {
  
    if(value==1){
      $roleType="Admin";
      return $roleType;
     }
     else{
        $roleType="User";
        return $roleType;
      
     }

 
  })

  