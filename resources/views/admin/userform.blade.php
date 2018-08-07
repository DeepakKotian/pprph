@extends('layouts.adminapp')

@section('content')

@section('title','Dashboard') 
@section('description','Dashboard Description') 

<div class="content-wrapper" id="users-app">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="">Home</a>
            </li>
            @if(!empty($data))
             <li class="breadcrumb-item active">Edit User  </li>
            @else
             <li class="breadcrumb-item active">Add User  </li>
            @endif
        </ol>
        <div class="row">
            <div class="col-12">
               <form action="" method="post" id="userForm" class="p-5">
                @if(!empty($data))
                    <input type="hidden" name="currentUserId" id="currentUserId"  value="{{ $data->id }}">
                @endif
                <div class="form-group row" :class="{ 'form-group--error': $v.user.name.$error }">
                        <label for="name" class="col-sm-2 form-label">Name</label>
                        <input type="text" name="name" class="form-control col-sm-4"  v-model="$v.user.name.$model" id="name"  placeholder="Enter Name">
                    </div>
                <div class="form-group row" :class="{ 'form-group--error': $v.user.email.$error }">
                        <label for="email" class="col-sm-2 form-label">Email address</label>
                        <input type="email"  name="email" class="form-control col-sm-4"  v-model="$v.user.email.$model" id="email"  placeholder="Enter email">
                    </div>
                    <div class="form-group row"  :class="{ 'form-group--error': $v.user.password.$error }">
                        @if(!empty($data))
                        <!-- <label for="password" class="col-sm-2 form-label">Change Password</label>
                        <input type="password" class="form-control col-sm-4" v-model="user.passwordNew" id="password" placeholder="Password"> -->
                        @else
                        <label for="password" class="col-sm-2 form-label">Change Password</label>
                        <input type="password" name="password" class="form-control col-sm-4" v-model="$v.user.password.$model"   id="password" placeholder="Password">
                        @endif
                    </div>
                    <div class="form-group row" :class="{ 'form-group--error': $v.user.usertype.$error }">
                        <label for="usertype" class="col-sm-2 form-label">User type</label>
                        <select name="usertype" class="form-control col-sm-2" id="usertype" v-model="$v.user.usertype.$model">
                            <option value="">Please select</option>
                            <option value="W">Admin</option>
                            <option value="1">User</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        @if(!empty($data))
                            <button type="button" class="btn btn-primary" v-on:click="updateUser()">Update</button>
                        @else
                            <button type="button" class="btn btn-primary" v-on:click="addNewUser()">Save</button>
                        @endif
                    </div>
                   
               </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{!! asset('js/users-app.js') !!}"></script>
@endsection
