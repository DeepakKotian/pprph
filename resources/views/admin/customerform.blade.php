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
    <li><a href="{{ url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    @if(!empty($data))
      <li class="breadcrumb-item active">Edit Customer  </li>
    @else
      <li class="breadcrumb-item active">Add Customer  </li>
    @endif
  </ol>
@stop
@section('content')
<div class="row" id="customer-app" v-cloak>

 <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Customer Form</h3>
              <div class="box-tools">
                <a  style="display:none;" class="btn btn-primary btn-md" data-toggle="modal" data-target="#logsModal" v-on:click="fetchLogs">Logs</a>
                <a class="btn btn-primary btn-md " href="{{ url('admin/customers') }}">Back to List Page</a>
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              @if($data)
              <input type="hidden" name="currentId" id="currentId" value="{{ $data->id }}">
              @endif
              <div class="box-body">
              <div class="col-sm-12">
                 
              </div>
              <div class="col-sm-5">
              <div class="row">
              <form class="form-vertical" action="" method="post">
                    @if($data)
                    <div class="form-group col-sm-4">
                      <label for="id">Customer Id</label>
                      <input type="text" class="form-control" v-model="customer.unique_id"  id="id"  placeholder="Customer Id" readonly>
                    </div>
                    @endif
                    <div class="form-group col-sm-4"> 
                      <label for="language">Language</label>
                      <select class="form-control" name="language" id="language" v-model="customer.language">
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
                    <div class="form-group col-sm-6">
                      <label for="dob">Date of Birth</label> 
                      <div class="input-group">
                      <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control datepicker" name="dob"  id="dob" placeholder="DOB" v-model="customer.dob">
                      </div>
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="company">Company</label>
                      <input type="text" class="form-control" name="company"   id="company" placeholder="Company" v-model="customer.company">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" cols="10" v-model="customer.address" rows="2"></textarea>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-4" :class="{ 'has-error': $v.customer.zip.$error }">
                      <label for="zip">Zip*</label>
                      <input type="text" class="form-control" name="zip"  id="zip" v-mask="'######'" placeholder="Postal Code" v-model="$v.customer.zip.$model">
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="city">City</label>
                      <input type="text" class="form-control" name="city"   id="city" placeholder="City" v-model="customer.city">
                    </div>
                    <div class="form-group col-sm-4">
                      <label for="nationality">Nationality</label>
                      
                      <select  class="form-control selectJS" id="nationality" placeholder="Nationality" v-model="customer.nationality">
                     
                      <option v-for="country in countries" v-bind:value="country.name"> <% country.name %> </option>
                        
                      </select>
                    </div>
                   
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.telephone.$error }">
                      <label for="telephone">Telephone</label>
                      <div class="input-group">
                      <div class="input-group-addon">
                      +41
                      </div>
                       <input type="text" class="form-control" name="telephone"  id="telephone" v-mask="'## ### ## ##'" placeholder="Telephone"  v-model="$v.customer.telephone.$model">
                      </div>
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="mobile">Mobile</label>
                      <div class="input-group">
                      <div class="input-group-addon">
                      +41
                      </div>
                      <input type="text" class="form-control" name="mobile" id="mobile" v-mask="'## ### ## ##'" placeholder="Mobile" v-model="customer.mobile">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.email.$error }">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" name="email"  id="email" placeholder="Email" v-model="$v.customer.email.$model">
                    </div>
                    <div class="form-group col-sm-6" :class="{ 'has-error': $v.customer.email_office.$error }">
                      <label for="telephone">Email office</label>
                      <input type="email" class="form-control" name="email_office"  id="email_office" placeholder="Emai Office" v-model="$v.customer.email_office.$model">
                       <input type="hidden" v-model="oldCustomerData" >
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
                                <th>Vertrag  </th>
                              </tr>
                            </thead>
                            <tbody>
                        
                              <tr v-for="(item, index) in customer.insurance">
                                <td>
                               
                                   <a href="" data-toggle="modal" :class="{'text-green':customer.policyArr.indexOf(item.id)>=0, 'text-red':customer.policyArr.indexOf(item.id)<0}" data-target="#insuranceModal" v-on:click="loadInsuranceModal(item)" ><% item.name %></a>
                                </td>
                                <td>
                                  <button  v-on:click="loadAntragModal(item)"  data-toggle="modal" data-target="#antragModal"  type="button" class="btn btn-default btn-sm">
                                  <i class="fa fa-square " :class="'text-green'"  ></i></button>
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
                            <h3 class="box-title familyToggle" >Add Family</h3>
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
                                <tr v-for="(fmly, index) in customer.family">
                                    <td> <% fmly.first_name %></td>
                                    <td> <% fmly.last_name %></td>
                                    <td> <% fmly.dob %></td>
                                    <td> <span v-show="fmly.mobile">+41</span> <% fmly.mobile %></td>
                                    <td>
                                      <a type="button" class="btn btn-default" data-toggle="modal" data-target="#familyModal" v-on:click="loadFamily(fmly)"><i class="fa fa-edit"></i></a> 
                                      <a type="button" data-toggle="modal" data-target="#deleteFamilyModal" v-on:click="loadFamily(fmly)" class="btn btn-default"><i class="fa fa-trash"></i></a>
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
                
                @if(!empty($data))
                    <a href="{{ url('/admin/customers/') }}" class="btn btn-primary" >Cancel </a>
                <!-- <button  class="btn btn-primary  " data-toggle="modal" v-on:click="taskapp.loadTaskDetail(null,currentId)" data-target="#addTask" >Add Task</button> -->
                    <button type="button" class="btn btn-primary" v-on:click="updateCustomer">Update</button>
                  <!-- <a target="_blank" href="{{ url('/admin/printcustomer/'.$data->id) }}" class="btn btn-primary" >Print</a> -->
                     &nbsp;
                    <div class="btn-group btn-toggle"> 
                    <button type="button" class="btn"  data-toggle="modal" data-target="#statusModal" class="btn btn-success" v-on:click="onStatus(1)" v-bind:class="'btn-success'"  v-bind:disabled="customer.status==1"   >ACTIVE</button>
                    <button  type="button" class="btn" data-toggle="modal" data-target="#statusModal" class="btn btn-danger" v-on:click="onStatus(0)" v-bind:class="'btn-danger'" v-bind:disabled="customer.status==0" >DEACTIVE</button>
                
                 
                  </div>
                @else
                  <button type="reset" v-on:click="resetForm" class="btn btn-info">Reset</button>
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
                        <div class="form-group ">
                          <label for="dob_family">DOB</label>
                          <div class="input-group">
                                <div class="input-group-addon">
                                 <i class="fa fa-calendar"></i>
                                </div>
                          <input type="text" class="form-control datepicker" name="dob_family"  id="dob_family"  v-model="family.dob_family" placeholder="DOB" >
                          </div>
                        </div>
                        <!-- <div class="form-group " :class="{ 'has-error': $v.family.nationality_family.$error }">
                          <label for="nationality_family">Nationality*</label>
                          <select  class="form-control" id="nationality_family" placeholder="Nationality" v-model="$v.family.nationality_family.$model">
                          <option v-for="country in countries" v-bind:value="country.name"> <% country.name %> </option>
                      </select>
                        </div>    -->
                        <div class="form-group ">
                            <label for="mobile_family">Mobile Number</label>
                            <div class="input-group">
                            <div class="input-group-addon">
                            +41
                            </div>
                            <input type="text" v-mask="'## ### ## ##'" class="form-control" name="mobile_family"  id="mobile_family"  v-model="family.mobile_family" placeholder="Mobile Number">
                            </div>
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
                      <h4 class="modal-title" id="exampleModalLabel"> <span style="text-transform:capitalize;"> <% currentCtgName %>  </span>  
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                          <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.provider_id.$error }">
                              <input type="hidden" name="insurance_ctg_id" id="insurance_ctg_id" v:bind:value="insurancedata.insurance_ctg_id" v-model="insurancedata.insurance_ctg_id">
                                <label for="providerSlct">Provider Name*</label>
                                <select sty="width:100%;" class="form-control" name="provider" id="providerSlct" v-model="$v.insurancedata.provider_id.$model" v-on:change="fetchPolicyList()">
                                  <option value="">Please Select</option>
                                  <option v-for="(vl, index) in providerslist" v-bind:value="vl.provider_id"  >  <% vl.providerName %></option>
                                </select>
                            </div>
                            <!-- <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.policy_number.$error }">
                                <label for="policy_number">Policy Number</label>
                                <input type="text" class="form-control" name="policy_number" v-model="$v.insurancedata.policy_number.$model"  id="policy_number"   placeholder="Policy Number">
                            </div> -->
                        </div>
                        <div class="row">
                          <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.start_date.$error }">
                              <label for="policy_number">Start Date</label>
                              <div class="input-group">
                                <div class="input-group-addon">
                                 <i class="fa fa-calendar"></i>
                                </div>
                              <input type="text" class="form-control" name="start_date"  id="start_date"   v-model="$v.insurancedata.start_date.$model"   placeholder="Start Date">
                          </div>
                          </div>
                          <div class="form-group col-sm-6" :class="{ 'has-error': $v.insurancedata.end_date.$error }">
                              <label for="policy_number">End Date</label>
                              <div class="input-group">
                                <div class="input-group-addon">
                                 <i class="fa fa-calendar"></i>
                                </div>
                              <input type="text" class="form-control" name="end_date"  id="end_date"  v-model="$v.insurancedata.end_date.$model"  placeholder="End Date">
                            </div>
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
                        <div class="row" v-show="policylist!=''">
                            <div class="form-group col-sm-12">
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Policy Number</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tr v-for="(policyRow,index) in policylist">
                                  <td> <% policyRow.policy_number %> </td>
                                  <td><% policyRow.start_date %></td>
                                  <td><% policyRow.end_date %></td>
                                  <td>
                                    <a href="javascript:void(0)" v-on:click="fetchPolicyDetail(policyRow.policy_id)" class="fa fa-edit"></a>
                                  </td>
                                </tr>
                              </table>
                            </div>
          
                         </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"  data-dismiss="modal">Cancel</button>
                        <button v-show="policyAction=='edit'"  class="btn btn-primary" type="button" v-on:click="savePolicy()">UPDATE </button>
                        <button v-show="policyAction==''" class="btn btn-primary" type="button" v-on:click="addNewPolicy()">ADD</button>
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
                      <button class="btn btn-primary"  type="button" data-dismiss="modal"  v-on:click="deleteFamily">Delete</button>
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
                    <div class="modal-body" v-show="customer.policy==''">Are you sure you want to <% statusText %> <b></b> ?</div>
                    <div class="modal-body" v-show="customer.policy!=''"> Please deactivate all active products to <% statusText %> </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" v-show="customer.policy==''" data-dismiss="modal" v-on:click="statusUpdate(currentId)" type="button">Yes</button>
                        <button class="btn btn-primary"  v-show="customer.policy!=''" data-dismiss="modal"  type="button">Yes</button>
                    </div>
            </div>

      </div>
    
    </div>
     <!-- Status Modal end-->
  <!-- logs modal -->
  <div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="logsModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Logs</h4>
                    </div>
                    <div class="modal-body" > 
                    
                        <ul>
                          <li v-for="(logs, index) in customerlogs">
                            <h3>Edited By : <% logs.userName %> on <% logs.updated_at %> </h3>
                            <div class="form-group">

                              <ul>
                                <li v-for="(rw,index) in logs.logArr">
                                    <div v-show="index=='first_name'">
                                       First Name : "<% rw.old_value %>" to <% rw.new_value %>
                                    </div>
                                    <div v-show="index=='last_name'">
                                       Last name : "<% rw.old_value %>" to <% rw.new_value %>
                                    </div>
                                    <div v-show="index=='company'">
                                       Company : "<% rw.old_value %>" to <% rw.new_value %>
                                    </div>
                                    <div v-show="index=='address'">
                                       Address: "<% rw.old_value %>" to <% rw.new_value %>
                                    </div>
                                </li>
                              </ul>
                            </div>
                          </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">OK</button>
                    </div>
            </div>
      </div>
    </div>
    <!-- logsmodal ends -->

     <!-- Antrag Modal -->
    <div class="modal fade" id="antragModal" tabindex="-1" role="dialog" aria-labelledby="antragModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel"> <% currentCtgName %> Antrag </h4>
                    </div>
                    <div class="modal-body">
                 <div class="row">
                    <div class="form-group col-sm-4">
                                <label for="providerSlct">Provider Name*</label>
                                <select sty="width:100%;" class="form-control" name="provider" id="providerSlct" v-model="$v.insurancedata.provider_id.$model" v-on:change="loadAntragForm(event)">
                                  <option value="0">Please Select</option>
                                  <option v-for="(prd, index) in providerslist" v-bind:value="prd.provider_id"  >  <% prd.providerName %></option>
                                </select>
                            </div>
                      </div>
                    <div style=" position: relative; display: block; height: 0;padding: 0; overflow: hidden; padding-bottom:69%">
                       <iframe v-show="isDocument" v-bind:src="urlPrefix+'../uploads/antrag/'+ currentAntragDocument" type="application/pdf" frameborder="0" width="100%" height="600px"></iframe>
                       <div v-show="isDocument==false" class="text-danger">
              
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
               No Document Found
              </div>
                    </div>
                  </div>
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
                        <h4 class="modal-title" id="exampleModalLabel"><% currentCtgName %> Vertrag </h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="vertragProviderSlct">Provider Name*</label>
                            <select sty="width:100%;" class="form-control" name="provider" id="vertragProviderSlct" v-model="$v.insurancedata.provider_id.$model" v-on:change="loadVertragPolicyList(event)" >
                              <option value="">Please Select</option>
                              <option v-for="(prd, index) in providerslist" v-bind:value="prd.provider_id"  >  <% prd.providerName %></option>
                            </select>
                        </div>
                        <div  class="form-group col-sm-4">
                          <label for="policy_id">Policy Number*</label>
                           <select sty="width:100%;" class="form-control" name="policy_id"  id="policy_id" v-on:change="loadDocuments()" >
                              <option value="">Please Select</option>
                              <option v-for="(pRow,index) in policylist" v-bind:value="pRow.policy_id"  >  <% pRow.policy_number %></option>
                            </select>
                        </div>
                      </div>
                      

                      <div class="row">
                        <div class="form-group col-sm-12" v-if="vertrag">
                          <div v-show="vertrag.document_name!==null">
                            <label for="">Contract Form: </label> <a target="_blank" v-bind:href="urlPrefix+'../uploads/vertrag/'+vertrag.document_name"> <% vertrag.document_name %> </a>
                          </div>
                          <div v-if="vertrag.document_name==null">
                            <span class="text-danger">Click on ADD DOCUMENT to upload contract form</span>
                          </div>
                          <h4 v-show="vertrag.policyDocs!=''">Documents Uploaded to Policy</h4>
                          <div class="table table-responsive"  v-show="vertrag.policyDocs!=''">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>File Name</th>
                                  <th>Actions</th>
                                </tr>
                                <tbody>
                                  <tr v-for="(doc, index) in vertrag.policyDocs">
                                    <td> <% doc.document_name %> </td>
                                    <td> <a class="fa fa-eye" target="_blank" v-bind:href="urlPrefix+'../uploads/vertrag/'+doc.document_name"></a> &nbsp;&nbsp;
                                    <a class="fa fa-download" download v-bind:href="urlPrefix+'../uploads/vertrag/'+doc.document_name"> </a> &nbsp;&nbsp;
                                    <a class="fa fa-trash" target="_blank" v-on:click="$('#docId'+doc.id).show()"></a>
                                    <div class="alert alert-warning alert-dismissible"  v-bind:id="'docId'+doc.id" role="alert" style="display:none;padding:5px; margin-bottom:0;">
                                      <button type="button" class="close" v-on:click="$('#docId'+doc.id).hide()" aria-label="Close" style="right:0;">
                                       <span aria-hidden="true">&times;</span>
                                      </button>
                                      <span aria-hidden="true"> <a href="javascript:void(0)" v-on:click="deletePolicyDocument(doc)" style="text-decoration:none"> Yes Delete!  <i class="fa fa-trash"></i> </a> </span>
                                    </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </thead>
                            </table>
                          </div>
                          <div class="form-group col-sm-12" >
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-primary" v-on:click="$('.documentAdd').toggle()"> ADD DOCUMENTS </button>
                            </div>
                          </div>
                          <div class="documentAdd" style="display:none;">
                           <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="">Choose Document Type</label>
                                <select class="form-control" name="documentType" id="documentType" v-on:change="checkDocumentType">
                                  <option value="">Please Select </option>
                                  <option value="0">Contract Form</option>
                                  <option value="1">Policy Documents</option>
                                </select>
                            </div>
                              <div class="form-group col-sm-6 otherdocs">
                                <label for="">Choose from Documents</label>
                                <select class="form-control" name="otherDocuments" id="otherDocuments"  v-on:change="checkDocument">
                                    <option value="">Please Select</option>
                                    <option v-for="(rw, index) in vertrag.allDocs" v-bind:value="rw.id"> <% rw.document_name %>  </option>
                                </select>
                                <span class="text-danger" v-show="vertrag.allDocs==''">No policy documents exist to add, Please upload a new file</span>
                              </div>
                                <div class="form-group col-sm-12 uploadDoc" >
                                  <label for="uploadDoc">Upload Document: </label> 
                                  <div class="input-group">
                                    <div class="input-group-btn">
                                      <button type="button" id="uploadFile"  v-on:click="$('#document').click()"  class="btn btn-primary">Choose file</button>
                                    </div> 
                                    <input type="text"  readonly class="form-control input-rounded" v-model="currentVertragDoc"   id="editDocumentfile"  >
                                    <input type="file" id="document" class="filestyle" v-on:change="uploadFile" style="display: none;">
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12" v-else="vertrag">
                            <h5>No Policy Added</h5>
                        </div>
                      </div>
                      
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary documentAdd" style="display:none;" v-on:click="uploadDocument"  type="button">Save</a>
                    </div>
            </div>

        </div>
      </div>
 <!-- Vertrag Modal End -->
</div>
<!-- <div id="task-app">
  @include('admin.taskmodal')
</div> -->

@stop
@section('js')
<script src="{!! asset('js/task-app.js') !!}"></script>
<script src="{!! asset('js/customer-app.js') !!}"></script>
<script>
 $(document).ready(function() {
       $('.selectJS').select2();
      
  });
 


</script>
@stop
