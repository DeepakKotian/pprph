var settingapp = new Vue({
    el: '#settings-app',
    data: {
      urlPrefix:urlPrefix,
      modalAction:'',
      language:{
          name:null,
          description:null,
          type:'language',
          status:null,
          lngid:'',
      },
      languageList:{},
    },
    validations:{
        language:{
          name:{
            required:required,
          },
          description:{
            required:required,
          },
    }
    },
  
    created: function(){
    
    }, 
    mounted: function(){
        if($('#languageTable').length>0){
           
            this.fetchLanguages();
        }    
     },

    methods: {
        loadLanguage(item){
            if(item!=null){
                this.modalAction="edit";
                this.language.name=item.name;
                this.language.description=item.description;
                this.language.lngid=item.id;
            }
            else{
                this.modalAction="add";
                this.language.name="";
                this.language.description="";
                this.language.lngid="";
            }
        this.$v.language.$reset();
        },
        
        fetchLanguages(){
            this.$http.get(this.urlPrefix+'language-list').then(function(response){
            $('#languageTable').DataTable().destroy();  
             this.languageList=response.data.data;
             this.loadDataTable();
            });
        },

        addLanguage(){
            if (this.$v.language.$invalid) {
                this.$v.language.$touch()
               }
              else{
                this.$http.post(this.urlPrefix+'add-language',this.language).then(function(response){
                    this.$toaster.success(response.data);
                    $('#languageTable').DataTable().destroy();
                    this.fetchLanguages();
                    $('#addLanguges').modal('hide');
                }).catch(function(response){
                    $(document).find('body .v-toaster .v-toast-error').remove();
                    this.$toaster.error(response.data);
                });
            }
        },
        updateLanguage(){
            if (this.$v.language.$invalid) {
                this.$v.language.$touch()
            }
            else{
                this.$http.post(this.urlPrefix+'update-language/'+ this.language.lngid ,this.language).then(
                    function(response){
                        this.$toaster.success(response.data);
                        this.fetchLanguages();
                        $('#addLanguges').modal('hide');
                    }
                ).catch(function(response){
                    if(response.data){
                     $(document).find('body .v-toaster .v-toast-error').remove();
                     this.$toaster.error(response.data);
                    }
                    
                 });
            }  
        },

        loadDataTable:function(){
            setTimeout( function(){ $('#languageTable').DataTable({ destroy: true, "order": [[0, "asc" ]],}); },1000)

        },
        loadStatusModal:function(item){
            if(item !== null){
                this.modalAction='changeStatus';
                this.language.name=item.name;
                this.language.lngid=item.id;
                this.language.status =  item.status;
            }
            // this.$v.insurance.$reset();  
            $('#statusModal').modal('show');
        },

        changeStatus:function(item){
            this.language =  item;
                this.$http.post(this.urlPrefix+'update-language-status',this.language).then(
                function(response){
                    this.$toaster.success(response.data);
                    $('#statusModal').modal('hide');
                    this.fetchLanguages();
                   
                }
            )
        },
  
    
    },
     
    delimiters: ["<%","%>"]
  })
