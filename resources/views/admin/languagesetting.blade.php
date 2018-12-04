@extends('adminlte::page')
@section('title', 'Languages')
@section('content_header')   
<h1>
Language List <small>View list of Languages</small>
</h1>

<ol class="breadcrumb">
   <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Language List</li>
</ol>
@stop
@section('content')
<!-- /.box-header -->
<div class="row">
<div class="col-lg-6  col-xs-12 col-md-12">
<div class="box box-primary" id="settings-app" v-cloak>
   <!-- Breadcrumbs-->
   <div class="box-header">
      <h3 class="box-title">View Language List </h3>
      <div class="box-tools">
         <a class="btn btn-primary btn-md pull-right" data-toggle="modal" v-on:click="loadLanguage(null)" data-target="#addLanguges" >Add New</a>
      </div>
   </div>

   <div class="box-body">
      <div class="col-12">
         <div class="table table-responsive">
            <table class="table table-bordered" id="languageTable">
               <thead>
                  <th>Sl.no</th>
                  <th>Name</th>
                  <th>Options</th>
               </thead>
               <tbody>
                  <tr v-for="(row,index) in languageList">
                   <td> <% index+1 %></td>
                   <td> <% row.description %></td>
                   <td>
                        <a data-toggle="modal"  data-target="#addLanguges"  v-on:click="loadLanguage(row)" class="btn btn-default"><i class="fa fa-edit"></i></a>
                        <a data-toggle="modal"   v-on:click="loadStatusModal(row)" class="btn btn-default" > <i class="fa fa-square" v-bind:class="{'text-green':row.status==1,'text-red':row.status==0}"></i> </a>
                      </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>



<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="modalLabel">Status?</h4>
         </div>
         <div class="modal-body">Are you sure you want to <span v-if="language.status ==1"> deactivate </span> <span v-if="language.status ==0"> activate </span> <b> <% language.description %>   </b> ?</div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary"   v-on:click="changeStatus(language)" type="button">Yes</a>
         </div>
      </div>
   </div>
</div>

 <!-- Add insurance -->
<div class="modal fade" id="addLanguges" tabindex="-1" role="dialog" aria-labelledby="addaddLangugesLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"  id="exampleModalLabel"><span style="text-transform:capitalize"><% modalAction %></span> Language </h4>
           
         </div>
         <div class="modal-body">
         <div class="box-body">
            <div class="form-group" :class="{ 'has-error': $v.language.description.$error }">
              <input type="hidden" class="form-control" id="exampleInputEmail1" v-model="language.type" placeholder="">
              <label for="exampleInputEmail1">Language *</label>
              <input type="text" class="form-control" id="exampleInputEmail1" v-model="$v.language.description.$model" placeholder="Enter Language">
            </div>
            <div class="form-group" :class="{ 'has-error': $v.language.name.$error }">
              <input type="hidden" class="form-control" id="exampleInputEmail1" v-model="language.type" placeholder="">
              <label for="exampleInputEmail1">Language code </label>
              <input type="text" class="form-control" id="exampleInputEmail1" v-model="$v.language.name.$model" placeholder="Enter Language code">
            </div>
               
         </div>
         </div>
         <div class="modal-footer">
          <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" v-if="modalAction=='edit'" v-on:click="updateLanguage()" type="button">Update</a>
          <button class="btn btn-primary" v-if="modalAction=='add'" v-on:click="addLanguage()" type="button">Save</a>
         </div>
      </div>
   </div>
</div>

</div>
</div>
</div>
@stop

@section('js')
<script src="{!! asset('js/settings-app.js') !!}"></script>

@stop