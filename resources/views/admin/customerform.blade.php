@extends('adminlte::page')
@section('title', 'Customer Form')
@section('content_header')
<h1> @if(!empty($data))
      Edit customer
     @else
      Add Customer  
     @endif
</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    @if(!empty($data))
      <li class="breadcrumb-item active">Edit Customer  </li>
    @else
      <li class="breadcrumb-item active">Add Customer  </li>
    @endif
  </ol>
@stop
@section('content')
<div class="row" id="customer-app">
 <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Customer Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-vertical" action="" method="post">
              @if($data)
              <input type="hidden" name="currentId" id="currentId" value="{{ $data->id }}">
              @endif
              <div class="box-body">
              <div class="col-sm-12">
                  
              </div>
              <div class="col-sm-5">
              <div class="row">
                    <div class="form-group col-sm-4">

                      <label for="id">Customer Id</label>
                      <input type="text" class="form-control" v-model="customer.id"  id="id"  placeholder="Customer Id" readonly>
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="language">Language*</label>
                      <select class="form-control" name="language" id="language" v-model="$v.customer.language.$model">
                        <option value="DE">DE</option>
                        <option value="EN">EN</option>
                        <option value="FR">FR</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="gender">Gender*</label>
                      <select class="form-control" name="gender" id="gender" v-model="$v.customer.gender.$model">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                      </select>
                    </div>
                  </div>
                 <div class="row">
                 
                    <div class="form-group col-sm-6">
                      <label for="first_name">First Name*</label>
                      <input type="text" class="form-control" name="first_name"  id="first_name" placeholder="First Name" v-model="$v.customer.first_name.$model">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="last_name">Last Name</label>
                      <input type="text" class="form-control" name="last_name"  id="last_name" placeholder="Last Name" v-model="$v.customer.last_name.$model">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="dob">Date of Birth*</label>
                      <input type="text" class="form-control datepicker" name="dob"    id="dob" placeholder="DOB" v-model="$v.customer.dob.$model">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="company">Company*</label>
                      <input type="text" class="form-control" name="company"  id="company" placeholder="Company" v-model="$v.customer.company.$model">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="address">Address*</label>
                    <textarea class="form-control" name="address" id="address" cols="10" rows="2" v-model="$v.customer.address.$model"></textarea>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="zip">Zip*</label>
                      <input type="text" class="form-control" name="zip"  id="zip" placeholder="Postal Code" v-model="$v.customer.zip.$model">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="nationality">Nationality*</label>
                      <input type="text" class="form-control" name="nationality"  id="nationality" placeholder="Company" v-model="$v.customer.nationality.$model">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="telephone">Telephone*</label>
                      <input type="text" class="form-control" name="telephone"  id="telephone" placeholder="Telephone" v-model="$v.customer.telephone.$model">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="telephone">Mobile</label>
                      <input type="text" class="form-control" name="mobile"  id="mobile" placeholder="Mobile" v-model="$v.customer.mobile.$model">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="email">Email*</label>
                      <input type="email" class="form-control" name="email"  id="email" placeholder="Email" v-model="$v.customer.email.$model">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="telephone">Email office</label>
                      <input type="email" class="form-control" name="email_office"  id="email_office" placeholder="Emai Office" v-model="$v.customer.email_office.$model">
                    </div>
                  </div>
                </div>
               <div class="col-sm-7">
                 <div class="box box-info" style="margin-top: 2.5%;">
                    <div class="box-header with-border">
                      <h3 class="box-title">Products</h3>
                    </div>
                    <div class="box-body">
                        <div class="table table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Insurance Name</th>
                                <th>Antrag</th>
                                <th>Vertrag</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(item, index) in customer.insurance">
                                  <td> <% item.name %></td>
                                  <td> <input type="checkbox"  :checked="customer.policyArr.indexOf(item.id)>=0" class="apply"> </td>
                                  <td>  <input type="checkbox"  :checked="customer.policyArr.indexOf(item.id)>=0" class="contract"> </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-12">
                  <div class="row">
                    <div class="form-group col-sm-5">
                      <div class="box box-info">
                          <div class="box-header with-border">
                            <h3 class="box-title">Add Family</h3>
                          </div>
                          <div class="box-body">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th> Name</th>
                                  <th>Last Name</th>
                                  <th>DOB</th>
                                  <th>Nationality</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="box-footer text-right">
                            <button type="button" data-toggle="modal" data-target="#familyModal" class="btn btn-primary">Add</button>
                          </div>
                          </div>
                      </div>
                    </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-center">
                 <button type="reset" class="btn btn-info">Reset</button>
                <button type="button" class="btn btn-primary" v-on:click="addNewCustomer">Submit</button>
              </div>
            </form>
            <div class="modal fade" id="familyModal" tabindex="-1" role="dialog" aria-labelledby="familyModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel">Add Family Member
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      </h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                            <label for="first_name_family">First Name*</label>
                            <input type="text" class="form-control" name="first_name_family"  id="first_name_family" v-model="$v.family.first_name_family.$model" placeholder="First Name" >
                        </div>
                        <div class="form-group ">
                            <label for="last_name_family">Last Name</label>
                            <input type="text" class="form-control" name="last_name_family"  id="last_name_family"  v-model="$v.family.last_name_family.$model" placeholder="Last Name">
                        </div>
                        <div class="form-group ">
                          <label for="dob_family">DOB*</label>
                          <input type="text" class="form-control datepicker" name="dob_family"  id="dob_family"  v-model="$v.family.dob_family.$model" placeholder="DOB" >
                        </div>
                        <div class="form-group ">
                          <label for="nationality_family">Nationality*</label>
                          <input type="text" class="form-control" name="nationality_family"  id="nationality_family"  v-model="$v.family.nationality_family.$model" placeholder="Nationality" >
                        </div>   
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                      <button class="btn btn-primary"  type="button"  v-on:click="saveFamilyMember">Save</a>
                    </div>
                 </div>
                </div>
               </div>
          </div>
        <!-- /.box -->
      </div>
</div>
@stop
@section('js')
<script src="{!! asset('js/customer-app.js') !!}"></script>
<script>
$('.datepicker').datepicker({
  autoclose: true
})
</script>
@stop
