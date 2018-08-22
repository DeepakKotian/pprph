
var app = new Vue({
    el: '#notification-app',
    data: {
      urlPrefix:urlPrefix,
      policyNotification:{
     
      },
      countNotify:'',
    },
  
    created: function(){
    
    }, 
    mounted: function(){
        this.loadNotifications();      
     },

    methods: {
        loadNotifications:function(item){
            this.$http.get(this.urlPrefix+'fetchnotification').then(
                function(response){
                    this.policyNotification  = response.data;
                    this.countNotify =  response.data.length;
                }
            )
        },
    },
     
    delimiters: ["<%","%>"]
  })

 
  