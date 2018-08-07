@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1> @if(!empty($data))
   Edit User
   @else
           Add User  
            @endif
        <small>it all starts here</small>
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

<div class="row" id="users-app">

        <!-- left column -->
        <div class="col-md-8">

          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
          </div>
         
          <form role="form">
          <div class="row">
          <div class="col-sm-8">
            <!-- /.box-header -->
            <!-- form start -->
          
                @if(!empty($data))
                        <input type="hidden" name="currentUserId" id="currentUserId"  value="{{ $data->id }}">
                @endif
                <div class="box-body">
                    <div class="form-group " :class="{ 'has-error': $v.user.first_name.$error }">
                            <label for="name"> First Name *</label>
                            <input type="text" name="first_name" class="form-control col-sm-4" v-model="$v.user.first_name.$model" id="first_name"  placeholder="Enter First Name">
                    </div>

                    <div class="form-group " >
                            <label for="name"> Last Name</label>
                            <input type="text" name="last_name" class="form-control col-sm-4" v-model="user.last_name" id="last_name"  placeholder="Enter Last Name">
                    </div>

                    <div class="form-group " :class="{ 'has-error': $v.user.email.$error }">
                            <label for="email">Email address *</label>
                            <input type="email"  name="email" class="form-control col-sm-4"  v-model="$v.user.email.$model" id="email"  placeholder="Enter email">
                    </div>

                    <div class="form-group "  :class="{ 'has-error': $v.user.password.$error }">
                        @if(!empty($data))
                        <!-- <label for="password" class="col-sm-2 form-label">Change Password</label>
                        <input type="password" class="form-control col-sm-4" v-model="user.passwordNew" id="password" placeholder="Password"> -->
                        @else
                        <label for="password" >Change Password</label>
                        <input type="password" name="password" class="form-control col-sm-4" v-model="$v.user.password.$model"   id="password" placeholder="Password">
                        @endif
                    </div>

                    <div class="form-group " :class="{ 'has-error': $v.user.role.$error }">
                        <label for="usertype" >Role *</label>
                        <select name="role" class="form-control col-sm-2" id="role" v-model="$v.user.role.$model">
                            <option value="">Please select</option>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                  
                        </select>
                    </div>
                    </div>
                    <div class="box-footer">
                        @if(!empty($data))
                            <button type="button" class="btn btn-primary" v-on:click="updateUser()">Update</button>
                        @else
                            <button type="button" class="btn btn-primary" v-on:click="addNewUser()">Save</button>
                        @endif
                    </div>
                    
                    </div>
                    <div class="col-sm-4">
                 
                        <div :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/portfolio/' +user.photo + ')' }"  id="user_photo_holder" class="img-custom-responsive"> </div>
                        <div class="btn-section">
                           <label type="button" for="user_photo" class="btn btn-w4y btn-rounded " >
                           <i class="fa fa-edit"></i>
                           </label>
                           <input type="file" class="op_0" id="user_photo" >
                        </div>
                
                    </div>
                    </div>
                    </form>
             
        </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('js')
<script src="{!! asset('js/users-app.js') !!}"></script>
<script>
$('#user_photo').change(function(){
         var input = this;
         var url = $(this).val();
         var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
         var fileName = input.files[0].name;
        // $('#portfolio_file').val();
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

