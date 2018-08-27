@extends('adminlte::page')
@section('title', 'Insurance')
@section('content_header')   
<h1>
   Insurance List <small>View list of Insurance</small>
</h1>

<ol class="breadcrumb">
   <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Insurance List</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="row">
<div class="col-sm-6">
<div class="box box-primary" id="insurance-app" v-cloak>
   <!-- Breadcrumbs-->
   <div class="box-header">
      <h3 class="box-title">View Insurance List </h3>
      <div class="box-tools">
         <a class="btn btn-primary btn-md pull-right" data-toggle="modal" v-on:click="loadinsurancemodal(null)" data-target="#addInsurance" >Add New</a>
      </div>
   </div>

   <div class="box-body">
      <div class="col-12">
         <div class="table table-responsive">
            <table class="table table-bordered" id="insuranceTable">
               <thead>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Options</th>
               </thead>
               <tbody>
                  <tr v-for="row in insuranceData">
                     <td><% row.id %></td>
                     <td><% row.name %> </td>
                    
                     <td>
                        <a type="button" data-toggle="modal"  data-target="#addInsurance"  v-on:click="loadinsurancemodal(row)" class="btn btn-default"><i class="fa fa-edit"></i></a>
                     
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
<div class="modal fade" id="addInsurance" tabindex="-1" role="dialog" aria-labelledby="addInsuranceLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"  id="exampleModalLabel"><span style="text-transform:capitalize"><% modalAction %></span> Insurance</h4>
           
         </div>
         <div class="modal-body">
         <div class="box-body">
            <div class="form-group" :class="{ 'has-error': $v.insurance.name.$error }">
              <input type="hidden" class="form-control" id="exampleInputEmail1" v-model="insurance.type" placeholder="">
              <label for="exampleInputEmail1">Insurance Name *</label>
              <input type="text" class="form-control" id="exampleInputEmail1" v-model="$v.insurance.name.$model" placeholder="Enter Insurance Name">
            </div>
               
         </div>
         </div>
         <div class="modal-footer">
          <button class="btn btn-primary" v-if="modalAction=='edit'" v-on:click="updateInsurance()" type="button">Save</a>
            <button class="btn btn-primary" v-if="modalAction=='add'" v-on:click="addNewInsurance()" type="button">Save</a>
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
@stop