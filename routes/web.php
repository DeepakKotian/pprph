<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::match(['get', 'post'], 'register', function(){
    return redirect('/login');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware('auth');
//Route::get('admin/users', 'Admin\AdminController@usersList');

Route::group(['prefix' => 'admin','middleware'=>'auth'], function () {
    Route::get('/dashboard', 'Admin\AdminController@dashboard');
    Route::get('/users', 'Admin\AdminController@usersList');
    Route::get('/user-form', 'Admin\AdminController@userForm');
    Route::get('/user-form/{id}', 'Admin\AdminController@userForm');
    Route::post('/user-form/{id}', 'Admin\AdminController@updateUser');
    Route::get('/fetchuser/{id}', 'Admin\AdminController@fetchUser');
    Route::post('/saveuser', 'Admin\AdminController@saveUser');
    Route::post('/userdatatable', 'Admin\AdminController@fetchAllUsers');
    Route::post('/deleteuser', 'Admin\AdminController@deleteUser');
   
    Route::post('/logout', 'Auth\LoginController@getLogout');
     // profile of login user
    Route::get('/profile', 'Admin\ProfileController@index');
    Route::get('/userdata', 'Admin\ProfileController@userData');
    Route::post('/updateprofile', 'Admin\ProfileController@updateProfile'); // profile of login user
    
    //Customer Management
    Route::get('/customers', 'Admin\CustomerController@index');
    Route::get('/customer-form', 'Admin\CustomerController@show');
    Route::post('/storecustomer', 'Admin\CustomerController@store');
    Route::get('/customer-form/{id}', 'Admin\CustomerController@show');
    Route::get('/fetchcustomer/{id}', 'Admin\CustomerController@fetchCustomer');
    Route::post('/fetchpolicydetail/{id}', 'Admin\CustomerController@fetchPolicyDetail');
    Route::post('/fetchpolicylist/{id}', 'Admin\CustomerController@fetchPolicyList');
    Route::get('/customer-search', 'Admin\CustomerController@search');

    Route::get('/customer-filter-data', 'Admin\CustomerController@getCustomFilterData');
    Route::post('/customer-form/{id}', 'Admin\CustomerController@update');
    Route::post('/storefamily', 'Admin\CustomerController@storeFamily');
    Route::post('/updatefamily', 'Admin\CustomerController@updateFamily');
    Route::post('/deletefamily', 'Admin\CustomerController@deleteFamily');
    Route::post('/savepolicy/{id}', 'Admin\CustomerController@savePolicy');
    Route::post('/addnewpolicy/{id}', 'Admin\CustomerController@addNewPolicy');
    Route::post('/statusupdate', 'Admin\CustomerController@statusUpdate');
    Route::post('/fetchdocuments', 'Admin\CustomerController@fetchDocuments');
    Route::post('/upload-document', 'Admin\CustomerController@uploadDocuments');
    Route::get('/printcustomer/{id}', 'Admin\CustomerController@printCustomer');
  
    
    // insurance management
    Route::get('/policy-mapping', 'Admin\insuranceController@policyMapping');
    Route::post('/fetchpolicymapping', 'Admin\insuranceController@fetchPolicyMapping');
    Route::post('/addpolicymapping', 'Admin\insuranceController@addPolicyMapping');
    Route::post('/updatepolicymapping', 'Admin\insuranceController@updatePolicyMapping');
    Route::post('/fetchinsurance', 'Admin\insuranceController@fetchInsurance');
    Route::resource('insurance-list', 'Admin\insuranceController');
    Route::post('/fetchprovider', 'Admin\providersController@fetchProvider');
    Route::post('/fetchproviderslist', 'Admin\providersController@fetchProvidersList'); //list the data by insure id
    Route::resource('providers-list', 'Admin\providersController');
    Route::post('update-insurance-status', 'Admin\insuranceController@updateStatus');

    //Document management
    Route::get('/documents', 'Admin\documentsController@index');
    Route::get('/document-filter-data', 'Admin\documentsController@getDocFilterData');
    Route::post('/document-detail', 'Admin\documentsController@show');
    Route::post('/delete-document', 'Admin\documentsController@delete');

    //task management
    
    Route::get('fetchtaskusers', 'Admin\taskController@fetchTaskUsers');
    Route::get('/mytask-list', function () {
        return view('admin.mytasks');
    });
    Route::post('fetchtasklist', 'Admin\taskController@fetchTaskList');
    Route::post('fetchmytasklist', 'Admin\taskController@fetchMyTaskList');
    Route::post('assigntask', 'Admin\taskController@assigntask');
    Route::resource('task-list', 'Admin\taskController');
    
    Route::get('/fetchnotification', 'Admin\AdminController@fetchNotification');
    Route::get('/appointment', 'Admin\AppointmentController@index');
    Route::get('/fetchappointments', 'Admin\AppointmentController@fetchAppointments');
    Route::post('/add-appointment', 'Admin\AppointmentController@store');
    Route::post('/update-appointment', 'Admin\AppointmentController@update');

    Route::get('export-file/{type}', 'Admin\CustomerController@exportFile')->name('export.file');

    Route::get('/provision', 'Admin\insuranceController@policyDetail');
    Route::get('/policy-filter-data', 'Admin\insuranceController@policyFilterData');
    Route::get('/update-policy-status', 'Admin\insuranceController@policyStatus');
    Route::get('/policy-search', 'Admin\insuranceController@search');
    
});
