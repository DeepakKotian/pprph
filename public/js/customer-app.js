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
    el: '#customer-app',
    data: {
      urlPrefix:"/admin/",
      currentId:"",
      customer:{
        id:null,
        gender:null,
        email:null,
        email_office:null,
        first_name:null,
        last_name:null,
        language:'',
        zip:null,
        dob:null,
        gender:null,
        is_family:null,
        telephone:null,
        parent_id:null,
        nationality:'',
      },
      customerData:[],
      errors:[],
      family:{
        nationality_family:null,
        first_name_family:null,
        dob_family:null,
        nationality_family:null,
      },
    },
    validations:{
        customer:{
          email:{
              required:required,
              email:email,
          },
          dob:{
            required:required,
          },
          email_office:{
            email:email,
         },
          zip:{
            required:required,
          },
          nationality:{
            required:required,
          },
          gender:{
            required:required,
          },
          language:{
            required:required,
          },
          address:{
            required:required,
          },
          company:{
            required:required,
          },
          first_name:{
            required:required,
          },
          last_name:{
            required:required,
          },
          telephone:{
            required:required,
            phoneRegx:phoneRegx,
          },
          mobile:{
            phoneRegx:phoneRegx,
         },
      },
      family:{
        nationality_family:{
            required:required,  
        },
        first_name_family:{
            required:required,
          },
        last_name_family:{
            required:required,
        },
        dob_family:{
            required:required,
        }
      },
    },
    created: function(){
        this.currentId = $('#currentId').val();
        
    }, 
    mounted: function(){
        if(this.currentId)
        this.getCustomerData();
     },

    methods: {
        addNewCustomer: function () {
          console.log(this.$v.customer);
          if (this.$v.customer.$invalid) {
            this.$v.$touch()
        }else{
            this.customer.photo= $('#customer_photo')[0].files[0];
         
            let formData= new FormData();
            formData.append('first_name',this.customer.first_name);
            formData.append('last_name',this.customer.last_name);
            formData.append('email',this.customer.email);
            formData.append('phone',this.customer.phone);
            formData.append('photo',this.customer.photo);
            formData.append('role',this.customer.role);
            formData.append('password', this.customer.password);
            this.$http.post(this.urlPrefix+'savecustomer',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(
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
     }, 
     saveFamilyMember:function(){
        if (this.$v.family.$invalid) {
            this.$v.family.$touch()
        }
     },
     getCustomerData: function () {
        this.$http.get(this.urlPrefix+'fetchcustomer/'+this.currentId).then(function(response){
        this.customer=response.data;
       
        });
      }, 

     updatecustomer: function (event) {
        if($('#customer_photo')[0].files[0])
        {
            this.customer.photo= $('#customer_photo')[0].files[0];
        }
        let formData= new FormData();
        formData.append('first_name',this.customer.first_name);
        formData.append('last_name',this.customer.last_name);
        formData.append('email',this.customer.email);
        formData.append('phone',this.customer.phone);
        formData.append('photo',this.customer.photo);
        formData.append('role',this.customer.role);
        if (this.$v.customer.$invalid) {
            this.$v.$touch()
        }
        else{
            this.$http.post(this.urlPrefix+'customer-form/'+this.currentId,formData,{
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

      updateProfile: function (event) {
        if($('#customer_photo')[0].files[0])
        {
            this.profile.photo= $('#customer_photo')[0].files[0];
        }
       
        let formData= new FormData();
        formData.append('first_name',this.profile.first_name);
        formData.append('last_name',this.profile.last_name);
        formData.append('email',this.profile.email);
        formData.append('phone',this.profile.phone);
        formData.append('photo',this.profile.photo);
        formData.append('role',this.profile.role);
     
        if (this.$v.profile.$invalid) {
            this.$v.profile.$touch()
        }
        else{
            this.$http.post(this.urlPrefix+'updateprofile/',formData,{
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
        $roleType="customer";
        return $roleType;
      
     }
 })

  