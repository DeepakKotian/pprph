@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1> @if(!empty($data))
   Edit User
   @else
           Add User  
            @endif
     
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      
        @if(!empty($data))
             <li class="breadcrumb-item active">Edit User  </li>
            @else
             <li class="breadcrumb-item active">Add User  </li>
            @endif
      </ol>
@stop

@section('content')

<div class="row" id="users-app" v-cloak>
<form class="form-horizontal">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
      <div :style="{ backgroundImage: 'url(' + urlPrefix + '../uploads/userphoto/' +user.photo + ')' }"  id="user_photo_holder" class="img-custom-responsive"> </div>
      <div class="btn-section userphtoinput">
      <label type="button" for="user_photo" class="btn btn-block btn-primary">Choose Photo</label>
      <input type="hidden"  class="form-control input-rounded"  v-model="user.photo" id="userphoto_file" style="margin-right: 20px;" >
        <input type="file" class="op_0" id="user_photo" >
      </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
       
        <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
      </ul>
      <div class="tab-content">
    
        <div class="tab-pane active" id="settings">
       
                @if(!empty($data))
                        <input type="hidden" name="currentUserId" id="currentUserId"  value="{{ $data->id }}">
                @endif
            
            <div class="form-group" :class="{ 'has-error': $v.user.first_name.$error }">
              <label for="inputName" class="col-sm-2 control-label">First Name *</label>

              <div class="col-sm-10">
              <input type="text" name="first_name" class="form-control col-sm-4" v-model="$v.user.first_name.$model" id="first_name"  placeholder="Enter First Name">
              </div>
            </div>

            <div class="form-group" >
              <label for="inputName" class="col-sm-2 control-label">Last Name </label>

              <div class="col-sm-10">
              <input type="text" name="last_name" class="form-control col-sm-4" v-model="user.last_name" v-bind:value="user.last_name" id="last_name"  placeholder="Enter Last Name">
              </div>
            </div>
             <div class="form-group" :class="{ 'has-error': $v.user.phone.$error }">
              <label for="inputName" class="col-sm-2 control-label">Phone </label>
              <div class="col-sm-10">
              <input type="text" name="phone"  class="form-control col-sm-4" v-model.trim.lazy="$v.user.phone.$model" id="phone"  placeholder="Enter Phone">
              <span v-if="!$v.user.phone.phoneRegx" :class="{ 'help-block': !$v.user.phone.phoneRegx } " > Enter Valid Phone Number </span>
              </div>
              
            </div>
            
            <div class="form-group" :class="{ 'has-error': $v.user.email.$error }">
              <label for="inputName" class="col-sm-2 control-label">Email *</label>

              <div class="col-sm-10">
              <input type="email"  name="email" class="form-control col-sm-4"  v-model.trim.lazy="$v.user.email.$model" id="email"  placeholder="Enter email">
              <span v-if="!$v.user.email.email" :class="{ 'help-block': !$v.user.email.email } " > Enter Valid Email </span>
              </div>
            </div>
            @if(!empty($data))
              @else
            <div class="form-group" :class="{ 'has-error': $v.user.password.$error }" >
              <label for="inputName" class="col-sm-2 control-label">Password * </label>

              <div class="col-sm-10">
        
              <input type="password" name="password" class="form-control col-sm-4" v-model="$v.user.password.$model"  id="password"  placeholder="Enter Password">
         
              </div>
            </div>
            @endif
            <div class="form-group" :class="{ 'has-error': $v.user.role.$error }">
              <label for="inputName" class="col-sm-2 control-label">Role *</label>

              <div class="col-sm-10">
              <select name="role" class="form-control col-sm-2" id="role" v-model="$v.user.role.$model">
                            <option value="">Please select</option>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                  
                        </select>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                       @if(!empty($data))
                            <button type="button" class="btn btn-primary" v-on:click="updateUser()">Update</button>
                        @else
                            <button type="button" class="btn btn-primary" v-on:click="addNewUser()">Save</button>
                        @endif
              </div>
            </div>
          
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
  <!-- /.col -->
  </form>
</div>
<!-- /.row -->

@stop

@section('js')
<script src="{!! asset('js/users-app.js') !!}"></script>
<script>

$('#user_photo').change(function(){
         var input = this;
         var url = $(this).val();
         var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
         var fileName = input.files[0].name;
         $('#userphoto_file').val();
         if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
         {
             var reader = new FileReader();
             reader.onload = function (e) {
               $('#user_photo_holder').css("background-image", "url("+e.target.result+")");
             }
           reader.readAsDataURL(input.files[0]);
         }
         else
         {
           //$('#img').attr('src', '/assets/no_preview.png');
         }
     });
     </script>
@stop

