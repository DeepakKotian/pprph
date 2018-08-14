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
                    @if($data)
                    <div class="form-group col-sm-4">
                      <label for="id">Customer Id</label>
                      <input type="text" class="form-control" v-model="customer.id"  id="id"  placeholder="Customer Id" readonly>
                    </div>
                    @endif
                    <div class="form-group col-sm-4"  :class="{ 'has-error': $v.customer.language.$error }">
                      <label for="language">Language*</label>
                      <select class="form-control" name="language" id="language" v-model="$v.customer.language.$model">
                        <option value="DE">DE</option>
                        <option value="EN">EN</option>
                        <option value="FR">FR</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-4" :class="{ 'has-error': $v.customer.gender.$error }">
                      <label for="gender">Gender*</label>
                      <select class="form-control" name="gender" id="gender" v-model="$v.customer.gender.$model">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                      </select>
                    </div>
                  </div>
                 <div class="row">
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.first_name.$error }">
                      <label for="first_name">First Name*</label>
                      <input type="text" class="form-control" name="first_name"  id="first_name" placeholder="First Name" v-model="$v.customer.first_name.$model">
                    </div>
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.last_name.$error }">
                      <label for="last_name">Last Name*</label>
                      <input type="text" class="form-control" name="last_name"  id="last_name" placeholder="Last Name" v-model="$v.customer.last_name.$model">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.dob.$error }">
                      <label for="dob">Date of Birth*</label> 
                      <input type="text" class="form-control datepicker" name="dob"  id="dob" placeholder="DOB" v-model="$v.customer.dob.$model">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="company">Company*</label>
                      <input type="text" class="form-control" name="company"  id="company" placeholder="Company" v-model="customer.company">
                    </div>
                  </div>
                  <div class="form-group" :class="{ 'has-error': $v.customer.language.$error }">
                    <label for="address">Address*</label>
                    <textarea class="form-control" name="address" id="address" cols="10" rows="2" v-model="$v.customer.address.$model"></textarea>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-4" :class="{ 'has-error': $v.customer.zip.$error }">
                      <label for="zip">Zip*</label>
                      <input type="text" class="form-control" name="zip"  id="zip" placeholder="Postal Code" v-model="$v.customer.zip.$model">
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="city">City</label>
                      <input type="text" class="form-control" name="city"  id="city" placeholder="City" v-model="customer.city">
                    </div>
                    <div class="form-group col-sm-4" :class="{ 'has-error': $v.customer.nationality.$error }">
                      <label for="nationality">Nationality*</label>
                      <input type="text" class="form-control" name="nationality"  id="nationality" placeholder="Nationality" v-model="$v.customer.nationality.$model">
                    </div>
                   
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.telephone.$error }">
                      <label for="telephone">Telephone*</label>
                      <input type="text" class="form-control" name="telephone"  id="telephone" placeholder="Telephone" v-model="$v.customer.telephone.$model">
                    </div>

                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.mobile.$error }">
                      <label for="telephone">Mobile</label>
                      <input type="text" class="form-control" name="mobile"  id="mobile" placeholder="Mobile" v-model="$v.customer.mobile.$model">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.email.$error }">
                      <label for="email">Email*</label>
                      <input type="email" class="form-control" name="email"  id="email" placeholder="Email" v-model="$v.customer.email.$model">
                    </div>
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.email_office.$error }">
                      <label for="telephone">Email office</label>
                      <input type="email" class="form-control" name="email_office"  id="email_office" placeholder="Emai Office" v-model="$v.customer.email_office.$model">
                    </div>
                    
                  </div>
               
                 
                </div>
               <div class="col-sm-7">
                 @if(!empty($data))
                 <div class="box box-info" style="margin-top: 2.5%;">
                    <div class="box-header with-border">
                      <h3 class="box-title">Products</h3>
                    </div>
                    <div class="box-body">
                        <div class="table table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th> Insurance Name</th>
                                <th>Antrag</th>
                                <th>Vertrag</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(item, index) in customer.insurance">
                          
                                  <td> <a href="" data-toggle="modal" data-target="#insuranceModal" v-on:click="loadInsuranceModal(customer)" ><% item.name %></a></td>
                                  <td>
                                  <button v-if="customer.policyArr.indexOf(item.id)>=0" type="button" class="btn btn-default btn-sm">
                              <i class="fa fa-square text-green" ></i></button>
                              <button v-if="customer.policyArr.indexOf(item.id)<0" type="button" class="btn btn-default btn-sm">
                              <i class="fa fa-square text-red" ></i></button>
                               </td>
                            <td>
                            <button v-if="customer.policyArr.indexOf(item.id)>=0" type="button" class="btn btn-default btn-sm">
                              <i class="fa fa-square text-green" ></i></button>
                              <button v-if="customer.policyArr.indexOf(item.id)<0" type="button" class="btn btn-default btn-sm">
                              <i class="fa fa-square text-red" ></i>
                              </button>
                            </td>

                              </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                    </div>
                    @endif
                </div>
                @if(!empty($data))
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
                                <tr v-for="(item, index) in customer.family">
                                   <td> <% item.first_name %></td>
                                    <td> <% item.last_name %></td>
                                    <td> <% item.dob %></td>
                                    <td> <% item.nationality %></td>
                                    <td>
                                      <a type="button" class="btn btn-default" data-toggle="modal" data-target="#familyModal" v-on:click="loadFamily(item)"><i class="fa fa-edit"></i></a> 
                                      <a type="button" data-toggle="modal" data-target="#deleteFamilyModal" v-on:click="loadFamily(item)" class="btn btn-default"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="box-footer text-right">
                            <button type="button" data-toggle="modal" data-target="#familyModal" v-on:click="loadFamily(null)" class="btn btn-primary">Add</button>
                          </div>
                          </div>
                      </div>
                      @endif
                    </div>
        
                </div>
              </div>
           
              <!-- /.box-body -->
              <div class="box-footer text-center">
                <button type="reset" class="btn btn-info">Reset</button>
                @if(!empty($data))
                  <button type="button" class="btn btn-primary" v-on:click="updateCustomer">Update</button>
  &nbsp;
                    <div class="btn-group btn-toggle"> 
                    <button type="button" class="btn" v-bind:class="{'btn-primary':customer.status==1,'btn-default':customer.status==0}" >ACTIVE</button>
                    <button  type="button" class="btn" v-bind:class="{'btn-primary':customer.status==0,'btn-default':customer.status==1}">DEACTIVE</button>
                  </div>
                @else
                  <button type="button" class="btn btn-primary" v-on:click="addNewCustomer">Save</button>
                @endif
                
              </div>
            </form>
            <div class="modal fade" id="familyModal" tabindex="-1" role="dialog" aria-labelledby="familyModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel"> <span style="text-transform:capitalize;"> <% modalAction %> </span> Family Member
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      </h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group" :class="{ 'has-error': $v.family.first_name_family.$error }">
                            <label for="first_name_family">First Name*</label>
                            <input type="text" class="form-control" name="first_name_family"  id="first_name_family" v-model="$v.family.first_name_family.$model" placeholder="First Name" >
                        </div>
                        <div class="form-group ">
                            <label for="last_name_family">Last Name</label>
                            <input type="text" class="form-control" name="last_name_family"  id="last_name_family"  v-model="family.last_name_family" placeholder="Last Name">
                        </div>
                        <div class="form-group " :class="{ 'has-error': $v.family.dob_family.$error }">
                          <label for="dob_family">DOB*</label>
                          <input type="text" class="form-control datepicker" name="dob_family"  id="dob_family"  v-model="$v.family.dob_family.$model" placeholder="DOB" >
                        </div>
                        <div class="form-group " :class="{ 'has-error': $v.family.nationality_family.$error }">
                          <label for="nationality_family">Nationality*</label>
                          <input type="text" class="form-control" name="nationality_family"  id="nationality_family"  v-model="$v.family.nationality_family.$model" placeholder="Nationality" >
                        </div>   
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                      <button v-if="modalAction=='add'" class="btn btn-primary"  type="button"  v-on:click="storeFamily">Save</button>
                      <button v-if="modalAction=='edit'" class="btn btn-primary"  type="button"  v-on:click="updateFamily">Update</button>
                    </div>
                 </div>
                </div>
               </div>
               <!-- ends  -->
               <div class="modal fade" id="insuranceModal" tabindex="-1" role="dialog" aria-labelledby="insuranceModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel"> <span style="text-transform:capitalize;">  </span> Insurance 
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      </h4>
                    </div>
                    <div class="modal-body">
                       <div class="form-group">
                            <label for="first_name_family">Provider Name*</label>
                            <select class="form-control" name="provider" >
                              <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group ">
                            <label for="policy_number">Policy Number</label>
                            <input type="text" class="form-control" name="policy_number"  id="policy_number"   placeholder="Policy Number">
                        </div>
                        <div class="form-group ">
                            <label for="policy_number">Start Date</label>
                            <input type="text" class="form-control" name="policy_number"  id="policy_number"   placeholder="Policy Number">
                        </div>
                        <div class="form-group ">
                            <label for="policy_number">End Date</label>
                            <input type="text" class="form-control" name="policy_number"  id="policy_number"   placeholder="Policy Number">
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                      <button class="btn btn-primary"  type="button" v-on:click="">Save</button>
                      <button class="btn btn-primary"  type="button"  v-on:click="">Apply</button>
                    </div>
                 </div>
                </div>
               </div>
               <!-- end -->
               <div class="modal fade" id="deleteFamilyModal" tabindex="-1" role="dialog" aria-labelledby="familyModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                      <h4 class="modal-title"> <span style="text-transform:capitalize;"> Delete Family Member
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      </h4>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete  ?
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                      <button class="btn btn-primary"  type="button"  v-on:click="deleteFamily">Delete</button>
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

</script>
@stop
