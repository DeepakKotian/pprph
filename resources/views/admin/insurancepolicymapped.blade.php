@extends('adminlte::page')
@section('title', 'Policy Mapping')
@section('content_header')   
<h1>
Policy Mapping <small>View Policy Mapping </small>
</h1>

<ol class="breadcrumb">
   <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Policy Mapping List</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="row">
<div class="col-sm-6">
<div class="box box-primary" id="insurance-app" v-cloak>
   <!-- Breadcrumbs-->
   <div class="box-header">
      <h3 class="box-title">View Policy Mapping List </h3>
      <div class="box-tools">
         <a class="btn btn-primary btn-md pull-right" data-toggle="modal" v-on:click="loadPolicyMappingmodal(null)" data-target="#addPolicyMapping" >Add Mapping</a>
      </div>
   </div>

   <div class="box-body">
      <div class="col-12">
         <div class="table table-responsive">
            <table class="table table-bordered" id="policyMappingTable">
               <thead>
                  <th>Id</th>
                  <th>Insurancre Name</th>
                  <th>Policy Name</th>
                  <th>Options</th>
               </thead>
               <tbody>
             
                  <tr v-for="row in policyMappingData">
                     <td><% row.id %></td>
                     <td><% row.insurance_name %> </td>
                     <td><% row.provider_name %> </td>
                     <td>
                        <a type="button" data-toggle="modal"  data-target="#addPolicyMapping"  v-on:click="loadPolicyMappingmodal(row)" class="btn btn-default"><i class="fa fa-edit"></i></a>
                     
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>

<!-- Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="exampleModalLabel">Delete?</h4>
         </div>
         <div class="modal-body">Are you sure you want to delete <b>   </b> ?</div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary"   v-on:click="deleteUser(currentUserId)" type="button">Yes</a>
         </div>
      </div>
   </div>
</div>
 <!-- Add insurance -->
<div class="modal fade" id="addPolicyMapping" tabindex="-1" role="dialog" aria-labelledby="addPolicymappingLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"  id="exampleModalLabel"><span style="text-transform:capitalize"><% modalAction %></span> Policy Mapping</h4>
           
         </div>
         <div class="modal-body">
         <div class="box-body">
            <div class="form-group" :class="{ 'has-error': $v.policyMappings.insure_id.$error }">
         
              <label for="exampleInputEmail1"> Select Insurance Name *</label>
              <select class="form-control select2" style="width: 100%;"  v-model="$v.policyMappings.insure_id.$model">
              <option value="" > Select Insurance Name</option>
                  <option v-for="rowinsureselect in insuranceslect" v-if="rowinsureselect.status==1"  v-bind:value="rowinsureselect.id" ><% rowinsureselect.name %></option>
                
              </select>
 
            </div>

            <div class="form-group" :class="{ 'has-error': $v.policyMappings.plcy_id.$error }">
          
              <label for="plcy_id"> Select Providers Name *</label>
              <select class="form-control select2" style="width: 100%;" v-model="$v.policyMappings.plcy_id.$model">
              <option value="" > Select Providers Name</option>
              <option v-for="rowprovideselect in providersselect" v-if="rowprovideselect.status==1" v-bind:value="rowprovideselect.id"  ><% rowprovideselect.name %></option>
                 
              </select>
 
            </div>
            <div class="form-group" >
            <label for="exampleInputFile">Upload Antrag Document: </label>
            <div class="input-group">
                  <span class="input-group-btn image-preview-input">
                      <span class="btn btn-primary btn-file image-preview-input-title">
                          Browse… <input type="file"   id="documentfile" name="input-file-preview"/>
                      </span>
                  </span>
                  <label   class="form-control image-preview-filename input-rounded"   id="editDocumentfile"  > <% policyMappings.ducumentData %></label>
              </div>
              </div>

               
         </div>
         </div>
         <div class="modal-footer">
          <button class="btn btn-primary" v-if="modalAction=='edit'" v-on:click="updatePolicymapping()" type="button"> Save</a>
          <button class="btn btn-primary" v-if="modalAction=='add'" v-on:click="addNewPolicymapping()" type="button">Save</a>
         </div>
      </div>
   </div>
</div>

</div>
</div>
</div>
@stop

@section('js')
<script src="{!! asset('js/insurance-app.js') !!}"></script>
<style> 
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

#img-upload{
    width: 100%;
}
</style>
<script>

        $(".image-preview-input input:file").change(function (){     
         
         var filedata = this.files[0];
         var reader = new FileReader();
       
             $(".image-preview-filename").text(filedata.name);  
        
      
         reader.readAsDataURL(filedata);
     });  
     
     </script>

@stop