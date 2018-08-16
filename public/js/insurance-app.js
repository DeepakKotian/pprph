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
      insuranceslect:{},
      providersselect:{},
      insuranceData:[],
      providers:{
        name:null,
        type:'provider',
      },

      providersData:[],
      policyMappings:{
        insure_id:null,
        plcy_id:null,
      },
      policyMappingData:[],
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
           
        },
        policyMappings:{
            insure_id:{
                required:required,
            },
            plcy_id:{
                required:required,
            },
        }
    },
    created: function(){
    
    }, 
    mounted: function(){
       
        if($('#insuranceTable').length>0){
            this.loadAllInsurance();
        }
        if($('#providersTable').length>0){
            this.loadAllProviders();
        }
        if($('#policyMappingTable').length>0){
            this.loadAllPolicyMapping();
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
                        $('#addInsurance').modal('hide');
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
                        $('#addInsurance').modal('hide');
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

        loadPolicyMappingmodal:function(item){
            this.modalAction='add';
            this.policyMappings.insure_id="";
            this.policyMappings.plcy_id="";
            this.loadDropDowninsurance();
            this.loadDropDownproviders();
            if(item !== null){
                this.modalAction='edit';
                this.policyMappings.insure_id=item.insurance_ctg_id;
                this.policyMappings.plcy_id=item.provider_id;
                this.policyMappings.mappingId=item.id;
                this.policyMappings.ducumentData = item.document_name;
            }
            this.$v.policyMappings.$reset();  
        },

        loadDropDowninsurance:function(){
            this.$http.post(this.urlPrefix+'fetchinsurance').then(
                function(response){
                    this.insuranceslect=response.data.data; 
                })

        },

        loadDropDownproviders:function(){
          
            this.$http.post(this.urlPrefix+'fetchprovider').then(
                function(response){
                    this.providersselect  = response.data.data;
                }
            )
           
        },

        loadDropDowninsurance:function(){
            this.$http.post(this.urlPrefix+'fetchinsurance').then(
                function(response){
                    this.insuranceslect=response.data.data; 
                })
           
        },

        addNewPolicymapping:function(){
            if (this.$v.policyMappings.$invalid) {
                this.$v.policyMappings.$touch()
             }
             else{
                this.policyMappings.ducumentData= $('#documentfile')[0].files[0];
                let formData= new FormData();
                formData.append('insure_id',this.policyMappings.insure_id);
                formData.append('policy_id',this.policyMappings.plcy_id);
                formData.append('documnetData',this.policyMappings.ducumentData);
                this.$http.post(this.urlPrefix+'addpolicymapping',formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                    }).then(
                    function(response){
                        this.$toaster.success(response.data);
                        $('#policyMappingTable').DataTable().destroy();
                        this.loadAllPolicyMapping();
                        
                    }
                ).catch(function(response){
                   if(response.data){
                    this.$toaster.error(response.data);
                   }
                   else{
                    let self = this;
                    $.each(response.data.errors, function(key, value){
                        self.$toaster.error(value[0]);
                    });
                   }
                    
                });
            } 
        }, 
        updatePolicymapping: function () {
      
            if($('#documentfile')[0].files[0])
            {
                this.policyMappings.ducumentData= $('#documentfile')[0].files[0];
            }
           
            let formData= new FormData();
          
            formData.append('insure_id',this.policyMappings.insure_id);
            formData.append('policy_id',this.policyMappings.plcy_id);
            formData.append('documnetData',this.policyMappings.ducumentData);
            formData.append('mappingId',this. policyMappings.mappingId);
           
            if (this.$v.policyMappings.$invalid) {
                this.$v.policyMappings.$touch()
            }
            else{
                this.$http.post(this.urlPrefix+'updatepolicymapping',formData,{
                    headers:{
                        'Content-Type': 'multipart/form-data'
                    }
                    }).then(
                    function(response){
                        this.$toaster.success(response.data);
                        $('#policyMappingTable').DataTable().destroy();
                        this.loadAllPolicyMapping();
                    }
                    ).catch(function(response){
                        this.$toaster.error(response.data);
                    });
            }
          },
       
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

        loadAllPolicyMapping:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchpolicymapping').then(
                function(response){
                    self.policyMappingData  = response.data.data;
            
                }
            )
            self.loadDataTable();
        },

        loadDataTable:function(){
          setTimeout( function(){ $('#insuranceTable').DataTable(); },500)
          setTimeout( function(){ $('#providersTable').DataTable(); },500)
          setTimeout( function(){ $('#policyMappingTable').DataTable(); },500)
            
        }

     },
     
    delimiters: ["<%","%>"]
  })

 
  