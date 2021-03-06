
var app = new Vue({
    el: '#customer-app',
    data: {
      urlPrefix:urlPrefix,
      currentId:"",
      statusText:"",
      statusId:"",
      countries:null,
      providerslist:[],
      currentAntragDocument:null,
      isDocument:false,
      policylist:'',
      policyAction:'',
      currentCtgName:'',
      isTooltip:null,
      languagedata:'',
      customerDocs:{

      },
      customerDocument:'',
      customerDocumentTitle:'',
      currentCustomerDoc:'',
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
        telephone:"",
        mobile:"",
        parent_id:null,
        nationality:'',
        appointments:'',
        city:'',
      },
      customerlogs:{
      },
      notes:{},
      singleNote:{ 
          description:"",
          noteId:""
       },
      oldCustomerData:{
          family:[]
      },
      sampleDta:[],
      currentVertragDoc:'',
      errors:[],
      family:{
        first_name_family:null,
        last_name_family:null,
        dob_family:null,
        nationality_family:null,
        mobile_family:null,
        unique_id_family:'',
        //email_family:null,
      },
      modalAction:'',
      insurancedata:{
          policy_number:'',
          start_date:null,
          end_date:null,
          family:[],
          insurance_ctg_id:'',
          provider_id:'',
          policy_id:'',
      },
      vertrag:{
        policyDocs:[],
      },
      vertragData:{
        
     },
    },
    validations:{
        customer:{
          email:{
              email:email,
          },
         email_office:{
            email:email,
         },
          zip:{
            required:required,
          },
          gender:{
            required:required,
          },
          first_name:{
            required:required,
          },
          last_name:{
            required:required,
          },
          telephone:{
              validateRequired:function (){
                if( (this.customer.mobile == null || this.customer.mobile == "") && (this.customer.telephone == null || this.customer.telephone =="")){
                    return false;
                }
                else
                    return true;
              }
          },
          mobile:{
              validateRequired:function (){
                    if( (this.customer.mobile == null || this.customer.mobile == "") && (this.customer.telephone == null || this.customer.telephone =="")){
                        return false;
                    }
                    else
                        return true;
              }
          },
      },
      family:{
        // email_family:{
        //     email:email,
        // },
        first_name_family:{
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
        
      }
    },
    
    created: function(){

        this.currentId = $('#currentId').val();
        this.family.parent_id = this.currentId;

        var self = this;
        $.getJSON(urlPrefix+'../js/countries.json', function (countries) {
            self.countries = countries.data;
           
        });

       this.languagelist();
      
       
    }, 

    mounted: function(){
   
        if(this.currentId)
        this.getCustomerData();
        
        let self = this;
        $('#dob').datepicker({
            format:'dd-mm-yyyy',
            startDate:'-50y',
            endDate:'0',
            todayHighlight: true,

        }).on(
            'changeDate', function() { self.customer.dob = $('#dob').val(); 
             $('#dob').datepicker('hide'); }
          )
        $('#dob_family').datepicker({
            format:'dd-mm-yyyy',
            startDate:'-50y',
            endDate:'0',
            todayHighlight: true
        }).on(
        'changeDate',  function() { self.family.dob_family = $('#dob_family').val();  $('#dob_family').datepicker('hide');  }
        )
        $('#end_date').datepicker({
            format:'dd-mm-yyyy',
            todayHighlight: true
        }).on(
            'changeDate',  function(selected) { self.insurancedata.end_date = $('#end_date').val(); 
            var maxDate = new Date(selected.date.valueOf());
            $('#start_date').datepicker('setEndDate', maxDate);  $('#end_date').datepicker('hide'); 
          }
        )
        $('#start_date').datepicker({
            format:'dd-mm-yyyy',
            todayHighlight: true,
            startDate:'-5y',
        }).on(
            'changeDate',  function(selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#end_date').datepicker('setStartDate', minDate);
                self.insurancedata.start_date =  $('#start_date').val(); 
                $('#start_date').datepicker('hide'); 
            }
        )
        $('#nationality').on('change',function(){
              self.customer.nationality = $(this).val();
        })
      
        // setTimeout(function()  {
        //     self.fetchNotes();
        //     self.notes.forEach(element => {
        //         if(element.description.length >20){
        //             self.isTooltip=true;
        //             console.log(element.description.length +' have tooltip' +self.isTooltip);
        //         }
        //         else{
        //             self.isTooltip=false;
        //             console.log(element.description.length +' no tooltip' + self.isTooltip );
        //         }
              
        //     }); 
        // }, 3000);
     },

    methods: {
        languagelist(){
            this.$http.get(this.urlPrefix+'language-list').then(function(response){
                response.data.data.forEach(function(element) {
                     if(element.status==0){
                        response.data.data.splice(element,1);
                     }
                 });
                 this.languagedata=response.data.data;
                });

        },
        resetForm: function () {
            var self = this; 
            Object.keys(this.customer).forEach(function(key,index) {
              self.customer[key] = '';
            });
          },

        addNewCustomer: function () {
           
        if (this.$v.customer.$invalid) {
            this.$v.customer.$touch();
        }else{
            this.$http.post(this.urlPrefix+'storecustomer',this.customer).then(
                function(response){
                    this.$toaster.success('Successfully Created');
                    setTimeout(function(){
                        //console.log(response.data.id);
                        window.location.href = urlPrefix+'customer-form/'+response.data.id;
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
        this.loadNotesDetail();
        // this.notes.forEach(element => {
        //     console.log(element);
            
        // });
        this.$http.get(this.urlPrefix+'fetchcustomer/'+this.currentId).then(function(response){
        this.oldCustomerData={
                id:response.data.id,
                unique_id:response.data.unique_id,
                status:response.data.status,
                gender:response.data.gender,
                email:response.data.email,
                email_office:response.data.email_office,
                first_name:response.data.first_name,
                last_name:response.data.last_name,
                language:response.data.language,
                zip:response.data.zip,
                company:response.data.company,
                city:response.data.city,
                address:response.data.address,
                dob:response.data.dob,
                country:response.data.country,
                gender:response.data.gender,
                is_family:response.data.is_family,
                telephone:response.data.telephone,
                mobile:response.data.mobile,
                parent_id:response.data.parent_id,
                nationality:response.data.nationality,
                user_id:response.data.user_id,
                family_member_id:response.data.family_member_id,
             };

        this.customer=response.data;
        setTimeout(function() {
            $('.selectJS').select2();
            $('#insuranceModal').find('.modal-body #selectJSFamily').select2();

        }, 1000);
        $( "#dob" ).datepicker( "setDate", this.customer.dob);
        });
      }, 

     updateCustomer: function (event) {
        if (this.$v.customer.$invalid) {
            this.$v.customer.$touch()
        }
        else{
            this.$http.post(this.urlPrefix+'customer-form/'+this.currentId,{customer:this.customer,oldCustomerData:this.oldCustomerData}).then(
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
        this.family.mobile_family = "";
        //this.family.email_family="";
        if(item !== null){
            this.modalAction='edit';
            this.family.first_name_family = item.first_name;
            this.family.last_name_family = item.last_name;
            this.family.dob_family = item.dob;
            this.family.nationality_family = item.nationality;
            this.family.mobile_family = item.mobile;
            this.family.unique_id_family = item.unique_id;
            this.family.id = item.id; 
            this.oldCustomerData.family = {
                    first_name_family:item.first_name,
                    last_name_family:item.last_name,
                    dob_family :item.dob,
                    nationality_family : item.nationality,
                    mobile_family : item.mobile,
                    unique_id_family : item.unique_id,
                    id : item.id,

            }
            $( "#dob_family" ).datepicker( "setDate", this.family.dob_family);
        }
        this.$v.family.$reset();  
      },
      
      storeFamily: function () {
  
        if (this.$v.family.$invalid) {
            this.$v.family.$touch();
        }else{
          
            this.$http.post(this.urlPrefix+'storefamily',this.family).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.getCustomerData();
                    this.$v.family.$reset();
                    $('#familyModal').modal('hide');
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
          
        if (this.$v.family.$invalid) {
            this.$v.family.$touch();
        }else{
           
            this.$http.post(this.urlPrefix+'updatefamily',{family:this.family,oldFamily:this.oldCustomerData.family}).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.getCustomerData();
                    $('#familyModal').modal('hide');
                }
            ).catch(function(response){
                let self = this;
                $.each(response.data.errors, function(key, value){
                    self.$toaster.error(value[0]);
                });
            });
        } 
      },
      onStatus:function(status){
        this.statusId=status;
       
        if(status=='1'){

           this.statusText="Activate";
        }
        else{
            this.statusText="Deactivate";
        }
    
      },

      statusUpdate:function(currentCustId){
        this.statusId= this.statusId;
        this.statusText=this.statusText;
        this.$http.post(this.urlPrefix+'statusupdate',{currentCustId:currentCustId,currentStatusId:this.statusId,statusText:this.statusText}).then(
            function(response){
                this.customer.status=this.statusId;
               this.$toaster.success(response.data);
            }
        )
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
           this.fetchProvidersData(item.id);
           this.insurancedata.start_date = '';
           this.insurancedata.family = [];
           this.insurancedata.end_date = '';
           this.insurancedata.policy_number = '';
           this.policyAction = '';
           this.insurancedata.provider_id='';
           this.policylist = '';
           this.currentCtgName =  item.name;
           this.$v.insurancedata.$reset();
           $('#insuranceModal').find('.modal-body #selectJSFamily').val('');
           $('#insuranceModal').find('.modal-body #selectJSFamily').trigger('change');
        },

        fetchPolicyDetail :function(id){
            this.policyAction = 'edit';
            this.insurancedata.start_date = '';
            this.insurancedata.family = [];
            this.insurancedata.end_date = '';
            this.insurancedata.policy_number = '';
            this.insurancedata.policy_id = parseInt(id);
            this.$http.post(this.urlPrefix+'fetchpolicydetail/'+this.currentId,  this.insurancedata).then(function(response){
                this.insurancedata.family = response.data.family;
                $('#insuranceModal').find('.modal-body #selectJSFamily').val(this.insurancedata.family);
                $('#insuranceModal').find('.modal-body #selectJSFamily').trigger('change');
                this.insurancedata.start_date = response.data.start_date;
                this.insurancedata.end_date = response.data.end_date;
                this.insurancedata.policy_number = response.data.policy_number;
                this.insurancedata.policy_id =  response.data.policy_id;
                $( "#start_date" ).datepicker( "setDate", this.insurancedata.start_date);
              //  $( "#end_date" ).datepicker( "setDate", this.insurancedata.end_date);
             }).catch(function(response){
                let self = this;
                self.$toaster.error(response.data);
                $('#insuranceModal').find('.modal-body #selectJSFamily').val('');
                $('#insuranceModal').find('.modal-body #selectJSFamily').trigger('change');
           });
          
        },
        fetchPolicyList :function(){
            this.insurancedata.provider_id = $('#providerSlct')[0].value;
            this.insurancedata.start_date = '';
            this.insurancedata.family = [];
            this.insurancedata.end_date = '';
            this.insurancedata.policy_number = '';
            this.$v.insurancedata.$reset();
         
            $('#insuranceModal').find('.modal-body #selectJSFamily').val('');
            $('#insuranceModal').find('.modal-body #selectJSFamily').trigger('change');
            this.policyAction = '';
            this.$http.post(this.urlPrefix+'fetchpolicylist/'+this.currentId,  this.insurancedata).then(function(response){  
                this.policylist =  response.data;

            })
            
            
        },
        savePolicy:function(){ //update new policy
            if(this.$v.insurancedata.$invalid){
               this.$v.insurancedata.$touch();
            }else{
             this.insurancedata.family =   $('#insuranceModal .modal-body .row').find('#selectJSFamily').val();
             this.$http.post(this.urlPrefix+'savepolicy/'+this.currentId,  this.insurancedata).then(function(response){
               this.getCustomerData();
               this.$toaster.success(response.data);
               this.fetchPolicyList();
           });
          }
        },
        addNewPolicy:function(){ //add new policy
            if(this.$v.insurancedata.$invalid){
                this.$v.insurancedata.$touch();
             }else{
              this.insurancedata.family =   $('#insuranceModal .modal-body .row').find('#selectJSFamily').val();
              this.$http.post(this.urlPrefix+'addnewpolicy/'+this.currentId,  this.insurancedata).then(function(response){
                // $('#start_date').val('').datepicker('update');
                // $('#end_date').val('').datepicker('update');
                // $('#start_date').data('datepicker').setDate(null);
                // $('#end_date').data('datepicker').setDate(null);
                this.getCustomerData();
                this.$toaster.success(response.data);
                this.fetchPolicyList();
            });
           }
        },
        fetchProvidersData:function(insureId){
            this.$http.post(this.urlPrefix+'fetchproviderslist', {insureId:insureId}).then(function(response){
              this.providerslist=response.data;
            
               //console.log(this.providerslist.document_name);
               
            });
        },

        loadAntragForm:function(item){
         this.insurancedata.provider_id = parseInt(item.target.value);
           let providersLength = this.providerslist.length
            for(var i = 0; i < providersLength; i++) {
               
                if( this.providerslist[i].provider_id == this.insurancedata.provider_id){
                  this.currentAntragDocument = this.providerslist[i].document_name;
                    if(this.currentAntragDocument==null){
                        this.isDocument=false;
                    
                    }
                    else{
                        this.isDocument=true;
                    
                    }

                  }
                  
            }
        },
        
        //antrag section
        loadAntragModal:function(item){
            this.fetchProvidersData(item.id);
            this.currentCtgName = item.name;
            this.insurancedata.provider_id="0";
            this.isDocument=false;
        },

        loadVertragModal:function(item){
            this.insurancedata.provider_id = '';
            $('#vertragModal').find(".modal-body #vertragProviderSlct").val('');
            $('#vertragModal').find(".modal-body #vertragProviderSlct").trigger('change');
            $('#vertragModal').find(".modal-footer .btn-primary.documentAdd").hide();
            $('#vertragModal').find(".modal-body #document").val('');
            $('#vertragModal').find(".modal-body #docTitle").val('');
            $('#vertragModal').find(".modal-body #editDocumentfile").val('');
            this.policylist = [];
           if(this.customer.policyArr.indexOf(item.id)>=0){
                $('#vertragModal').modal('show');
                //this.vertrag = '';
                this.fetchProvidersData(item.id);
                this.insurancedata.insurance_ctg_id = item.id;
                this.currentCtgName = item.name;
                this.loadDocuments();
            }
        },
        loadDocuments:function(){
           //this.insurancedata.policy_id = $('#policy_id')[0].value;
           this.currentVertragDoc = '';
           $('#otherDocuments').val('');
           this.$http.post(this.urlPrefix+'fetchdocuments', { insurance_ctg_id:this.insurancedata.insurance_ctg_id, provider_id:this.insurancedata.provider_id, customer_id:this.currentId }).then(function(response){
                this.vertrag.policyDocs = response.data;
          });
        },
        loadVertragPolicyList:function(item){
          
            this.insurancedata.provider_id = parseInt(item.target.value);
            this.$http.post(this.urlPrefix+'fetchpolicylist/'+this.currentId,  this.insurancedata).then(function(response){  
                this.policylist =  response.data;
                $('#policy_id').val(''); $('#policy_id').trigger('change');
                this.loadDocuments();
            })
        },
        uploadFile: function(){
         this.currentVertragDoc = $('#document')[0].files[0].name;
        },
        uploadCustomerFile: function(){
            this.currentCustomerDoc = $('#customerDocument')[0].files[0].name;
        },
        checkDocumentType:function(event){
            if(event.target.value==0){
                $('.otherdocs').hide();
                $('.uploadDoc').show();
            }else{
                $('.otherdocs').show();
            }
        },
        checkDocument:function(event){
            if(event.target.value!=''){
                $('.uploadDoc').hide();
            }else{
                $('.uploadDoc').show();
            }
        },
        uploadDocument:function(){
            let docSize = $('#document')[0].files[0].size;
            if(docSize>2097152){
                this.$toaster.error('Document should not be more than 2 MB');
                return;
            }
            this.vertragData.document = $('#document')[0].files[0];
            let formData= new FormData();
            // formData.append('policy_id',this.vertrag.policy_id);
            formData.append('documentData',this.vertragData.document);
            formData.append('title', $('#docTitle').val());
            formData.append('insureId', this.insurancedata.insurance_ctg_id);
            formData.append('documnetType',0); //$('#documentType').val() - By default New Document
            formData.append('customer_id',this.currentId);
            if($('#documentType').val()!=0){
                formData.append('document_id',$('#otherDocuments').val());
            }
            this.$http.post(this.urlPrefix+'upload-document',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                }).then(
                function(response){
                    this.$toaster.success(response.data);
                    this.loadDocuments();
                    $('#documentType').val('');
                    $('#document').val('');
                    $('#docTitle').val('');
                    $("#editDocumentfile").val('');
                    $('#vertragModal').find(".modal-body #document").val('');
                    $('#vertragModal').find(".modal-body #docTitle").val('');
                    $('#vertragModal').find(".modal-body #editDocumentfile").val('');
                }
            ).catch(function(response){
                let self = this;
                self.$toaster.error(response.data);
                // $.each(response.data.errors, function(key, value){
                //     self.$toaster.error(value[0]);
                // });
            });
        },
        deletePolicyDocument:function(doc){
            this.$http.post(this.urlPrefix+'delete-document',  doc).then(function(response){
                this.loadDocuments();
                this.$toaster.success(response.data); 
                this.fetchPolicyList();
            }
          )
        },
        uploadCustomerDocument:function(){
            let docSize = $('#customerDocument')[0].files[0].size;
            if(docSize>2097152){
                this.$toaster.error('Document should not be more than 2 MB');
                return;
            }
            this.customerDocument = $('#customerDocument')[0].files[0];
            let formData= new FormData();
            formData.append('documentData',this.customerDocument);
            formData.append('title',this.customerDocumentTitle);
            formData.append('customer_id',this.currentId);
             this.$http.post(this.urlPrefix+'upload-customer-document',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                }).then(
                function(response){
                    this.fetchCustomerDocs();
                    this.$toaster.success(response.data);
                    this.customerDocument = '';
                    this.customerDocumentTitle= '';
                    this.currentCustomerDoc ='';
                    $('#customerDocument').val('');
                   
                }
            ).catch(function(response){
                let self = this;
                self.$toaster.error(response.data);
                // $.each(response.data.errors, function(key, value){
                //     self.$toaster.error(value[0]);
                // });
                
            });
        },
        deleteCustomerDocument:function(doc){
            this.$http.post(this.urlPrefix+'delete-customer-document',  doc).then(function(response){
                this.fetchCustomerDocs();
                this.$toaster.success(response.data); 
                $('#custdocId'+doc.id).hide();
            }
          )
        },
        
        fetchLogs:function(){
            this.$http.get(this.urlPrefix+'fetch-logs/'+this.currentId).then(function(response){
                this.customerlogs = response.data;
            }
          )
        },

        loadNotesDetail:function(note){
            //this.modalAction='';
            if(note!=null){
                this.modalAction='update';
                this.singleNote.description=note.description;
                this.singleNote.noteId=note.id;
            }
            else{
                this.modalAction='add'
                this.singleNote.description="";
                this.singleNote.noteId="";
                // this.currentId=this.currentId;
                // console.log(this.currentId);
                this.fetchNotes();
                
                
            }
        },
        /*notes section*/
        fetchNotes:function(){
            this.$http.post(this.urlPrefix+'fetch-notes',{ custId:this.currentId}).then(function(response){
                this.notes = response.data; 
                //console.log(this.notes);
               
               
            }
         )
        },
        addNote:function(){
            this.$http.post(this.urlPrefix+'notes',{ singleNote:this.singleNote,custId:this.currentId} ).then(function(response){
                this.singleNote.description="";
                this.singleNote.noteId="";
                this.fetchNotes();
            }).catch(function(response){

           });
        },
        editNote:function(){
           
            this.$http.put(this.urlPrefix+'notes/'+this.singleNote.noteId,{ singleNote:this.singleNote,custId:this.currentId} ).then(function(response){
                this.fetchNotes();
                this.modalAction='add'
                this.singleNote.description="";
                this.singleNote.noteId="";
            }).catch(function(response){

           });
        },
        deleteNote:function(noteId){
            this.$http.delete(this.urlPrefix+'notes/'+noteId).then(function(response){
                    this.fetchNotes();
                    this.modalAction='add'
                }
            )
        },
        fetchCustomerDocs:function(){
            this.$http.get(this.urlPrefix+'fetch-customer-docs/'+this.currentId).then(function(response){
                this.customerDocs = response.data;
            }
          ) 
        },
        saveAsCustomer:function(){
            this.$http.post(this.urlPrefix+'save-as-customer',this.family).then(function(response){
                window.location.href = urlPrefix+'customer-form/'+response.data;
            }
          ) 
        },
        postCodeMap:function(){
            this.$http.post(this.urlPrefix+'postcode-map',this.customer).then(function(response){
          
                this.customer.city = response.data;
               // this.oldCustomerData.city=response.data;
            }
          ) 
        }
      },
    delimiters: ["<%","%>"]
  })

//   Vue.filter('checkIndex', function (value) {
//      console.log(value);
//  })

  