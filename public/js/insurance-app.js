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
    el: '#insurance-app',
    data: {
      urlPrefix:"/admin/",
      modalAction:"",
      insurance:{
       name:null,
       type:'category',
      },
      insuranceData:[],
      providers:{
        name:null,
        type:'provider',
      },
      providersData:[],
      errors:[],

    },

    validations:{
        insurance:{
            name:{
                required:required,
            },
        },
             providers:{
            name:{
                required:required,
            },
           
        }
    },
    created: function(){
     //  this.loadAllInsurance();
    }, 
    mounted: function(){
        if($('#insuranceTable').length>0){
            this.loadAllInsurance();
        }
        if($('#providersTable').length>0){
            this.loadAllProviders();
        }
     },

    methods: {

        loadinsurancemodal:function(item){
            this.modalAction='add';
            this.insurance.name="";
            if(item !== null){
                this.modalAction='edit';
                this.insurance.name=item.name;
                this.insurance.id=item.id;
            }
            this.$v.insurance.$reset();  
        },

       

        addNewInsurance: function () {
          if (this.$v.insurance.$invalid) {
            this.$v.insurance.$touch()
           }
          else{
                this.$http.post(this.urlPrefix+'insurance-list',this.insurance).then(
                    function(response){
                        this.$toaster.success(response.data);
                        this.loadAllInsurance();
                     
                    }
                ).catch(function(response){
                    let self = this;
                    $.each(response.data.errors, function(key, value){
                        self.$toaster.error(value[0]);
                    });
                });
           } 
        }, 

       updateInsurance: function () {
        if (this.$v.insurance.$invalid) {
            this.$v.insurance.$touch()
           }
          else{
                this.$http.put(this.urlPrefix+'insurance-list/'+ this.insurance.id ,this.insurance).then(
                    function(response){
                        this.$toaster.success(response.data);
                        this.loadAllInsurance();
                      
                    }
                ).catch(function(response){
                    let self = this;
                    $.each(response.data.errors, function(key, value){
                        self.$toaster.error(value[0]);
                    });
                });
           } 
      },

      loadprovidersmodal:function(item){
        this.modalAction='add';
        this.providers.name="";
        if(item !== null){
            this.modalAction='edit';
            this.providers.name=item.name;
            this.providers.id=item.id;
        }
        this.$v.providers.$reset();  
    },

    addNewProvider:function () {
        if (this.$v.providers.$invalid) {
          this.$v.providers.$touch()
         }
        else{
              this.$http.post(this.urlPrefix+'providers-list',this.providers).then(
                  function(response){
                      this.$toaster.success(response.data);
                      this.loadAllProviders();
                    
                  }
              ).catch(function(response){
                  let self = this;
                  $.each(response.data.errors, function(key, value){
                      self.$toaster.error(value[0]);
                  });
              });
         } 
      }, 

      updateProvider:function () {
      if (this.$v.providers.$invalid) {
          this.$v.providers.$touch()
         }
        else{
              this.$http.put(this.urlPrefix+'providers-list/'+ this.providers.id ,this.providers).then(
                  function(response){
                      this.$toaster.success(response.data);
                      this.loadAllProviders();
                    
                  }
              ).catch(function(response){
                  let self = this;
                  $.each(response.data.errors, function(key, value){
                      self.$toaster.error(value[0]);
                  });
              });
         } 
    },

      
    //   onDelete:function(data){
        
    //      this.currentUserId = data.id;
    //      this.user.first_name = data.first_name;
    //      this.user.last_name = data.last_name;
    //   },
      
    //   deleteInsurance:function(userId){
      
    //         this.$http.post(this.urlPrefix+'deleteuser',{userId:userId}).then(
    //             function(response){
    //                $('#insuranceTable').DataTable().destroy();
    //                this.loadAllUsers();
    //                $('#deleteModal').modal('hide');
    //                this.$toaster.success(response.data);
    //             }
    //         )
    //     },

        loadAllInsurance:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchinsurance').then(
                function(response){
                    self.insuranceData  = response.data.data;
                   
               
                }
            )
            self.loadDataTable();
        },

        loadAllProviders:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchprovider').then(
                function(response){
                    self.providersData  = response.data.data;
                  
                }
            )
            self.loadDataTable();
        },
        loadDataTable:function(){
            setTimeout( function(){ $('#insuranceTable').DataTable(); },500)
            setTimeout( function(){ $('#providersTable').DataTable(); },500)
        }

     },
     
    delimiters: ["<%","%>"]
  })

 
  