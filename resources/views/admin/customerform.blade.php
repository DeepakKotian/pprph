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
                      <label for="nationality">Nationality* </label>
                      
                      <select  class="form-control selectJS" id="nationality" placeholder="Nationality" v-model="$v.customer.nationality.$model">
                     
                      <option v-for="country in countries" v-bind:value="country.name"> <% country.name %> </option>
                        
                      </select>
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
                                <td>
                                   <a href="" data-toggle="modal" data-target="#insuranceModal" v-on:click="loadInsuranceModal(item)" ><% item.name %></a>
                                </td>
                                <td>
                                  <button  v-on:click="loadAntragModal(item)"  data-toggle="modal" data-target="#antragModal"  type="button" class="btn btn-default btn-sm">
                                  <i class="fa fa-square " :class="{'text-green':customer.policyArr.indexOf(item.id)>=0, 'text-red':customer.policyArr.indexOf(item.id)<0}" ></i></button>
                                </td>
                                <td>
                                <button  type="button" class="btn btn-default btn-sm" v-on:click="loadVertragModal(item)">
                                  <i class="fa fa-square " :class="{'text-green':customer.policyArr.indexOf(item.id)>=0, 'text-red':customer.policyArr.indexOf(item.id)<0}" ></i></button></button>
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
                                  <th>Mobile</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(item, index) in customer.family">
                                   <td> <% item.first_name %></td>
                                    <td> <% item.last_name %></td>
                                    <td> <% item.dob %></td>
                                    <td> <% item.mobile %></td>
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
                    <button type="button" class="btn"  data-toggle="modal" data-target="#statusModal" class="btn btn-default" v-on:click="onStatus(1)"  v-bind:class="{'btn-primary':customer.status==1,'btn-default':customer.status==0}" >ACTIVE</button>
                    <button  type="button" class="btn" data-toggle="modal" data-target="#statusModal" class="btn btn-default" v-on:click="onStatus(0)"  v-bind:class="{'btn-primary':customer.status==0,'btn-default':customer.status==1}">DEACTIVE</button>
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
                        <!-- <div class="form-group " :class="{ 'has-error': $v.family.nationality_family.$error }">
                          <label for="nationality_family">Nationality*</label>
                          <select  class="form-control" id="nationality_family" placeholder="Nationality" v-model="$v.family.nationality_family.$model">
                          <option v-for="country in countries" v-bind:value="country.name"> <% country.name %> </option>
                      </select>
                        </div>    -->
                        <div class="form-group " :class="{ 'has-error': $v.family.mobile_family.$error }">
                            <label for="mobile_family">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile_family"  id="mobile_family"  v-model="family.mobile_family" placeholder="Mobile Number">
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
                        <div class="row">
                          <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.provider_id.$error }">
                              <input type="hidden" name="insurance_ctg_id" id="insurance_ctg_id" v:bind:value="insurancedata.insurance_ctg_id" v-model="insurancedata.insurance_ctg_id">
                                <label for="first_name_family">Provider Name*</label>
                                <select sty="width:100%;" class="form-control" name="provider" id="providerSlct" v-model="$v.insurancedata.provider_id.$model" v-on:change="fetchPolicyDetail(event)">
                                  <option value="">Please Select</option>
                                  <option v-for="(vl, index) in customer.providers" v-bind:value="vl.id"  >  <% vl.name %></option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.policy_number.$error }">
                                <label for="policy_number">Policy Number</label>
                                <input type="text" class="form-control" name="policy_number" v-model="$v.insurancedata.policy_number.$model"  id="policy_number"   placeholder="Policy Number">
                            </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.start_date.$error }">
                              <label for="policy_number">Start Date</label>
                              <input type="text" class="form-control" name="start_date"  id="start_date"   v-model="$v.insurancedata.start_date.$model"   placeholder="Start Date">
                          </div>
                          <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.end_date.$error }">
                              <label for="policy_number">End Date</label>
                              <input type="text" class="form-control" name="end_date"  id="end_date"  v-model="$v.insurancedata.end_date.$model"  placeholder="End Date">
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-sm-12">
                            <label for="policy_number">Choose Family Members</label>
                            <div>
                              <!-- <label class="fmlyChk"  v-for="(val, index) in customer.family"> 
                                <input type="checkbox" v-model="insurancedata.family[index]" v-bind:value="val.id"> <% val.first_name %> <% val.last_name %> <% insurancedata.family[index] %>
                              </label> -->
                              </div>
                              <select class="form-control selectJSFamily" id="selectJSFamily"  multiple width="100%">
                                <option v-for="(val, index) in customer.family" v-bind:value="val.id"><% val.first_name %> <% val.last_name %></option>
                              </select>
                            
                          </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                      <button class="btn btn-primary" type="button" v-on:click="savePolicy()">Save</button>
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
<!-- Status Modal -->
      <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Delete?</h4>
                    </div>
                    <div class="modal-body">Are you sure you want to <% statusText %> <b></b> ?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" data-dismiss="modal" v-on:click="statusUpdate(currentId)" type="button">Yes</a>
                    </div>
            </div>

      </div>
    
    </div>
     <!-- Status Modal end-->
     <!-- Antrag Modal -->
    <div class="modal fade" id="antragModal" tabindex="-1" role="dialog" aria-labelledby="antragModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel"> Antrag </h4>
                    </div>
                    <div class="modal-body">
                 <div class="row">
                    <div class="form-group col-sm-4">
                              <input type="hidden" name="insurance_ctg_id" id="insurance_ctg_id" v:bind:value="insurancedata.insurance_ctg_id" v-model="insurancedata.insurance_ctg_id">
                                <label for="first_name_family">Provider Name*</label>
                                <select sty="width:100%;" class="form-control" name="provider" id="providerSlct" v-model="$v.insurancedata.provider_id.$model" v-on:change="fetchPolicyDetail(event)">
                                  <option value="">Please Select</option>
                                  <option v-for="(vl, index) in customer.providers" v-bind:value="vl.id"  >  <% vl.name %></option>
                                </select>
                            </div>
                      </div>
                    <div style=" position: relative; display: block; height: 0;padding: 0; overflow: hidden; padding-bottom:69%">
                    <embed src="{{ url('/uploads/antrag/5b765506d86091534481670.pdf')}}" frameborder="0" width="100%" height="600px">
                  </div></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" data-dismiss="modal"  type="button">Yes</a>
                    </div>
            </div>

        </div>
      </div>
 <!-- Antrag Modal End -->


 <!-- Vertrag Modal -->
 <div class="modal fade" id="vertragModal" tabindex="-1" role="dialog" aria-labelledby="vertragModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel"> Vertrag </h4>
                    </div>
                    <div class="modal-body">
                      <div style="text-align: center;">
                        <embed src="{{ url('/uploads/antrag/5b765506d86091534481670.pdf')}}" frameborder="0" width="100%" height="400px">
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" data-dismiss="modal"  type="button">Yes</a>
                    </div>
            </div>

        </div>
      </div>
 <!-- Vertrag Modal End -->
</div>
@stop
@section('js')

<script src="{!! asset('js/customer-app.js') !!}"></script>
<script>
 $(document).ready(function() {
       $('.selectJS').select2();
  });
</script>
@stop
