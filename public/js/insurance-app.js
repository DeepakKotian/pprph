var app = new Vue({
    el: '#insurance-app',
    data: {
      urlPrefix:urlPrefix,
      modalAction:"",
      insurance:{
       name:null,
       type:'category',
       status:'',
      },
      insuranceslect:{},
      providersselect:{},
      insuranceData:[],
      providers:{
        name:null,
        type:'provider',
      },
      sortIndex:[],
      providersData:[],
      policyMappings:{
        insure_id:'',
        plcy_id:'',
        ducumentData:'',
        
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
        this.loadDropDowninsurance();
        this.loadDropDownproviders();
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
                        $('#insuranceTable').DataTable().destroy();
                        this.loadAllInsurance();
                        $('#addInsurance').modal('hide');
                    }
                ).catch(function(response){
                    if(response.data){
                       $(document).find('body .v-toaster .v-toast-error').remove();
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
                    if(response.data){
                     $(document).find('body .v-toaster .v-toast-error').remove();
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
                        $('#providersTable').DataTable().destroy();
                        this.loadAllProviders();
                        $('#addproviders').modal('hide'); 
                    }
                ).catch(function(response){
                    if(response.data){
                     $(document).find('body .v-toaster .v-toast-error').remove();
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
                    if(response.data){
                     $(document).find('body .v-toaster .v-toast-error').remove();
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

        loadPolicyMappingmodal:function(item){
            this.modalAction='add';
            this.policyMappings.insure_id="";
            this.policyMappings.plcy_id="";
            this.policyMappings.mappingId="";
            this.policyMappings.ducumentData = "";
            $(".image-preview-filename").text("");  
            if(item !== null){
                $('#documentfile').val('');
                this.modalAction='edit';
                this.policyMappings.insure_id=item.insurance_ctg_id;
                this.policyMappings.plcy_id=item.provider_id;
                this.policyMappings.mappingId=item.id;
                this.policyMappings.ducumentData = item.document_name;
                $(".image-preview-filename").text(this.policyMappings.ducumentData);  
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

        addNewPolicymapping:function(){
            if (this.$v.policyMappings.$invalid) {
                this.$v.policyMappings.$touch()
             }
             else{
                if($('#documentfile')[0].files[0]){
                    this.policyMappings.ducumentData= $('#documentfile')[0].files[0];
                }
                 else{
                    this.policyMappings.ducumentData=null;
                 }

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
                      // this.policyMappings.ducumentData=null;
                        this.$toaster.success(response.data);
                        $('#policyMappingTable').DataTable().destroy();
                        this.loadAllPolicyMapping();
                        $('#addPolicyMapping').modal('hide'); 
                        
                    }
                ).catch(function(response){
                    console.log(response.data.errors);
                  
                    let self = this;
                    $.each(response.data.errors, function(key, value){
                        self.$toaster.error(value[0]);
                    });
                  // this.policyMappings.ducumentData=null;
                });
            } 
        }, 
        updatePolicymapping: function () {
      
            if($('#documentfile')[0].files[0])
            {
                this.policyMappings.ducumentData= $('#documentfile')[0].files[0];
            }
            else{
              this.policyMappings.ducumentData= $(".image-preview-filename").text();  
                console.log(this.policyMappings.ducumentData); 
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
                        $('#documentfile').val('');
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
                    $('#insuranceTable').DataTable().destroy();
                    self.insuranceData  = response.data.data; 
                  
                }

            )
            self.loadDataTable();
        },
  

        loadAllProviders:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchprovider').then(
                function(response){
                    $('#providersTable').DataTable().destroy();
                    self.providersData  = response.data.data;
              
                }
            )
            self.loadDataTable();
        },

        loadAllPolicyMapping:function(){
            let self = this;
            this.$http.post(this.urlPrefix+'fetchpolicymapping').then(
                function(response){
                    $('#policyMappingTable').DataTable().destroy();
                    self.policyMappingData  = response.data.data;
                   
                }
            )
            self.loadDataTable();
        },

        loadDataTable:function(){
          setTimeout( function(){ $('#insuranceTable').DataTable({ destroy: true, "order": [[0, "asc" ]],}); },1000)
          setTimeout( function(){ $('#providersTable').DataTable({ destroy: true, "order": [[0, "asc" ]],}); },1000)
          setTimeout( function(){ $('#policyMappingTable').DataTable({destroy: true,"order": [[0, "asc" ]],}); },1000)
    
        },

        loadStatusModal:function(item){
            if(item !== null){
                this.modalAction='changeStatus';
                this.insurance.name=item.name;
                this.insurance.id=item.id;
                this.insurance.status =  item.status;
            }
            // this.$v.insurance.$reset();  
            $('#statusModal').modal('show');
        },

        changeStatus:function(item){
            this.insurance =  item;
                this.$http.post(this.urlPrefix+'update-insurance-status',this.insurance).then(
                function(response){
                    this.$toaster.success(response.data);
                    $('#statusModal').modal('hide');
                    this.loadAllProviders();
                    this.loadAllInsurance();
                    //this.loadDataTable();
                }
            )
        },
        reorderData:function(index){
            console.log(this.sortIndex[index]);
            if(this.sortIndex[index] >= 0) {
                let currentElem = this.insuranceData[index];
                this.insuranceData.splice(index,1);
                this.insuranceData = this.insuranceData.slice(0, this.sortIndex[index]).concat([currentElem]).concat(this.insuranceData.slice(this.sortIndex[index], this.insuranceData.length));
                console.log(this.insuranceData);
                this.$http.post(this.urlPrefix+'change-order',{data : this.insuranceData}).then(function(response){
                    $(document).find('body .v-toaster .v-toast-success').remove();
                    this.$toaster.success(response.data);
                    this.sortIndex = [];
                    // this.loadAllProviders();
                    //this.loadAllInsurance();
                    //this.loadDataTable();
                })
            }
        }
 },
     
    delimiters: ["<%","%>"]
  })

 
  