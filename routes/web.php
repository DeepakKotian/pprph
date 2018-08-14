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
    Route::get('/customer-filter-data', 'Admin\CustomerController@getCustomFilterData');
    Route::post('/customer-form/{id}', 'Admin\CustomerController@update');
    Route::post('/storefamily', 'Admin\CustomerController@storeFamily');
    Route::post('/updatefamily', 'Admin\CustomerController@updateFamily');
    Route::post('/deletefamily', 'Admin\CustomerController@deleteFamily');

    // insurance management
    Route::get('/policy-mapping', 'Admin\insuranceController@policyMapping');
    Route::post('/fetchpolicymapping', 'Admin\insuranceController@fetchPolicyMapping');
    Route::post('/addpolicymapping', 'Admin\insuranceController@addPolicyMapping');
    Route::post('/updatepolicymapping', 'Admin\insuranceController@updatePolicyMapping');
    
    Route::post('/fetchinsurance', 'Admin\insuranceController@fetchInsurance');
    Route::resource('insurance-list', 'Admin\insuranceController');
    Route::post('/fetchprovider', 'Admin\providersController@fetchProvider');
    Route::resource('providers-list', 'Admin\providersController');

    //Document management
    Route::get('/documents', 'Admin\documentsController@index');
    Route::get('/document-filter-data', 'Admin\documentsController@getDocFilterData');

    
  
});
