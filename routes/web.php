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

//Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/admin', function () {
//     return view('admin.dashboard');
// })->middleware('auth');

//Route::get('admin/users', 'Admin\AdminController@usersList');

Route::group(['prefix' => 'admin','middleware'=>'auth'], function () {
    Route::get('/', 'HomeController@index');
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
    Route::post('/change-password','Admin\AdminController@changePassword');
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
    Route::get('/fetch-logs/{id}', 'Admin\CustomerController@fetchLogs');
    Route::get('/fetch-customer-docs/{id}', 'Admin\documentsController@fetchCustomerDocs');
    Route::post('/delete-customer-document', 'Admin\documentsController@deleteCustomerDocument');
    Route::post('/upload-customer-document', 'Admin\documentsController@uploadCustomerDocument');
    Route::post('/save-as-customer', 'Admin\CustomerController@saveAsCustomer');
    Route::post('/postcode-map', 'Admin\CustomerController@postCodeMap');

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
    Route::get('download-pdf','Admin\CustomerController@downloadPDF');
    
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
    Route::post('change-order', 'Admin\insuranceController@changeOrder');
    
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
    Route::post('fetch-initial-task', 'Admin\taskController@fetchInitialTask');
    Route::post('fetchtasklist', 'Admin\taskController@fetchTaskList');
    Route::post('fetchmytasklist', 'Admin\taskController@fetchMyTaskList');
    Route::post('assigntask', 'Admin\taskController@assigntask');
    Route::post('fetchtaskhistory', 'Admin\taskController@fetchTaskHistory');
    Route::post('delete-task', 'Admin\taskController@deleteTask');
    Route::resource('task-list', 'Admin\taskController');
    
    Route::get('/fetchnotification', 'Admin\AdminController@fetchNotification');
    Route::get('/appointment', 'Admin\AppointmentController@index');
    Route::get('/fetchappointments', 'Admin\AppointmentController@fetchAppointments');
    Route::post('/add-appointment', 'Admin\AppointmentController@store');
    Route::post('/update-appointment', 'Admin\AppointmentController@update');
    Route::get('/fetchcustomersforappointment', 'Admin\AppointmentController@fetchCustomersForAppointment');
    Route::post('/deleteappointment', 'Admin\AppointmentController@deleteAppointment');
    
    Route::post('export-file', 'Admin\CustomerController@exportFile')->name('export.file');

    Route::get('download-excel','Admin\CustomerController@downloadExcel');

    Route::get('/provision', 'Admin\insuranceController@policyDetail');
    Route::get('/policy-filter-data', 'Admin\insuranceController@policyFilterData');
    Route::get('/update-policy-status', 'Admin\insuranceController@policyStatus');
    Route::get('/policy-search', 'Admin\insuranceController@search');

    // dashboard
    Route::post('/fetch-due-task', 'Admin\DashboardController@fetchDueTask');
    Route::post('/fetch-due-appointments', 'Admin\DashboardController@fetchDueAppointments');
    Route::post('/fetch-due-insurance', 'Admin\DashboardController@fetchExpiredInsurance');
    Route::post('/fetch-due-provisions', 'Admin\DashboardController@fetchprovision');
    

    //notes
    Route::resource('notes', 'Admin\NotesController');
    Route::post('fetch-notes', 'Admin\NotesController@fetchNotes');

    //settings
    Route::get('/language-setting', function () {
        return view('admin.languagesetting');
    });
    Route::get('/language-list', 'Admin\SettingsController@fetchLanguages');
    Route::post('/add-language', 'Admin\SettingsController@addLanguage');
    Route::post('/update-language/{id}', 'Admin\SettingsController@updateLanguage');
    Route::post('/update-language-status', 'Admin\SettingsController@updateLanguageStatus');
    
});
