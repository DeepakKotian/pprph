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
        email_family:null,
      },
      modalAction:'',
      insurancedata:{
          policy_number:'',
          start_date:'',
          end_date:'',
          family:[],
          insurance_ctg_id:'',
          provider_id:''
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
        email_family:{
            email:email,
        },
        first_name_family:{
            required:required,
          },
        dob_family:{
            required:required,
        }
      },
      insurancedata:{
          provider_id:{
              required:required,
          },
          start_date:{
              required:required,
          },
          end_date:{
            required:required,
          },
          policy_number:{
            required:required,
          }
      }
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
        $('#start_date').datepicker().on(
            'changeDate', () => { this.insurancedata.start_date = $('#start_date').val() }
        )
        $('#end_date').datepicker().on(
            'changeDate', () => { this.insurancedata.end_date = $('#end_date').val() }
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
            $('.selectJSFamily').select2();
        }, 1000);
         
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
        this.family.email_family="";
        if(item !== null){
            this.modalAction='edit';
            this.family.first_name_family = item.first_name;
            this.family.last_name_family = item.last_name;
            this.family.dob_family = item.dob;
            this.family.nationality_family = item.nationality;
            this.family.email_family = item.email;
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
           this.insurancedata.insurance_ctg_id =  item.id;
           $('#insuranceModal').find('.modal-body .selectJsFamily').select2();
        },
        fetchPolicyDetail :function(item){
            this.insurancedata.provider_id = parseInt(item.target.value);
            this.insurancedata.start_date = '';
            // this.insurancedata.family = [];
            this.insurancedata.end_date = '';
            this.insurancedata.policy_number = '';
            console.log(this.insurancedata.family.length);
            this.$http.post(this.urlPrefix+'fetchpolicydetail/'+this.currentId,  this.insurancedata).then(function(response){
                this.insurancedata.family = response.data.family;
                this.insurancedata.start_date = response.data.start_date;
                this.insurancedata.end_date = response.data.end_date;
                this.insurancedata.policy_number = response.data.policy_number;
            });
        },
        savePolicy:function(){
            console.log(this.$v.insurancedata.$invalid);
            if(this.$v.insurancedata.$invalid){
               this.$v.insurancedata.$touch();
            }else{
                //let self = this;
                // $('#insuranceModal').find('.modal-body input[type="checkbox"]:checked').each(function(){
                //     console.log( self.insurancedata.family);
                //     self.insurancedata.family.push(this.value);
                //   })
                console.log(this.insurancedata.family)
             this.$http.post(this.urlPrefix+'savepolicy/'+this.currentId,  this.insurancedata).then(function(response){
                this.insurancedata = response.data;
            });
          }
        },
        checkIndex:function(arr,val){
            console.log($.inArray(val,arr));
            if($.inArray(val,arr)>=0){
                return true;
            }
            return false;
        }
     },
    
     
    delimiters: ["<%","%>"]
  })

//   Vue.filter('checkIndex', function (value) {
//      console.log(value);
//  })

  