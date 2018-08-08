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
    return view('welcome');
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
    Route::post('/logout', 'Admin\Auth\LoginController@getLogout');
    Route::post('/userdatatable', 'Admin\AdminController@fetchAllUsers');
    Route::post('/deleteuser/{id}', 'Admin\AdminController@deleteUser');

    //Customer Management
    Route::get('/customers', 'Admin\CustomerController@index');
    Route::get('/user-form', 'Admin\CustomerController@show');
    Route::get('/user-form/{id}', 'Admin\CustomerController@show');
    Route::get('/customer-filter-data', 'Admin\CustomerController@getCustomFilterData');
  
});


