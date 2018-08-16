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
<<<<<<< HEAD
      urlPrefix:"/admin/",
=======
      urlPrefix:urlPrefix,
>>>>>>> 58d5f077c93308b7fb74de796e94883f9764bbd9
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
        first_name_family:null,
        last_name_family:null,
        dob_family:null,
        nationality_family:null,
      },
      modalAction:'',
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
        dob_family:{
            required:required,
        }
      },
    },
    created: function(){
        this.currentId = $('#currentId').val();
        this.family.parent_id = this.currentId;
    }, 
    mounted: function(){
        if(this.currentId)
        this.getCustomerData();
        $('#dob').datepicker().on(
            'changeDate', () => { this.customer.dob = $('#dob').val() }
          )
        $('#dob_family').datepicker().on(
        'changeDate', () => { this.family.dob_family = $('#dob_family').val() }
        )
        let self = this;
        $('#nationality').on('change',function(){
              self.customer.nationality = $(this).val();
        })
       
     },

    methods: {
        addNewCustomer: function () {
            console.log(this.customer.nationality);
            console.log(this.$v.customer.nationality);
        if (this.$v.customer.$invalid) {
            this.$v.customer.$touch();
        }else{
            this.$http.post(this.urlPrefix+'storecustomer',this.customer).then(
                function(response){
                    this.$toaster.success(response.data);
                    setTimeout(function(){
                        window.location.href = '/admin/customers'
                    },2000)
                }
            ).catch(function(response){
                let self = this;
                $.each(response.data.errors, function(key, value){
                    self.$toaster.error(value[0]);
                  });
            });
        } 
     }, 
     getCustomerData: function () {
        this.$http.get(this.urlPrefix+'fetchcustomer/'+this.currentId).then(function(response){
        this.customer=response.data;
        setTimeout(function() {
            $('.selectJS').select2();
        }, 100);
         
        });
      }, 

     updateCustomer: function (event) {
        console.log(this.customer);
        if (this.$v.customer.$invalid) {
            this.$v.customer.$touch()
        }
        else{
            this.$http.post(this.urlPrefix+'customer-form/'+this.currentId,this.customer).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.getCustomerData();
                }
            ).catch(function(response){
                this.$toaster.error(response.data);
            });
        }
      },
      loadFamily:function(item){
        this.modalAction='add';
        this.family.first_name_family="";
        this.family.last_name_family="";
        this.family.dob_family="";
        this.family.nationality_family="";
        if(item !== null){
            this.modalAction='edit';
            this.family.first_name_family = item.first_name;
            this.family.last_name_family = item.last_name;
            this.family.dob_family = item.dob;
            this.family.nationality_family = item.nationality;
            this.family.id = item.id; 
        }
        this.$v.family.$reset();  
      },
      
      storeFamily: function () {
          console.log(this.$v.family);
        if (this.$v.family.$invalid) {
            this.$v.family.$touch();
        }else{
            console.log(this.family);
            this.$http.post(this.urlPrefix+'storefamily',this.family).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.getCustomerData();
                }
            ).catch(function(response){
                let self = this;
                $.each(response.data.errors, function(key, value){
                    self.$toaster.error(value[0]);
                  });
            });
        } 
      },
      updateFamily: function () {
            console.log(this.$v.family);
        if (this.$v.family.$invalid) {
            this.$v.family.$touch();
        }else{
            console.log(this.family);
            this.$http.post(this.urlPrefix+'updatefamily',this.family).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.getCustomerData();
                }
            ).catch(function(response){
                let self = this;
                $.each(response.data.errors, function(key, value){
                    self.$toaster.error(value[0]);
                });
            });
        } 
      },
      deleteFamily: function () {
            this.$http.post(this.urlPrefix+'deletefamily',this.family).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.getCustomerData();
                }
            )
        },
        loadInsuranceModal :function(item){
           console.log(item)
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

  