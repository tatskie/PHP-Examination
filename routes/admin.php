<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::prefix('admin')->as('admin.')->group(function () {
	Route::get('/dashboard', 'Admin\DashboardController@index');

	// Company Controller
	Route::get('company', 'Admin\CompanyController@index')->name('company.index');
	Route::post('company/show', 'Admin\CompanyController@show')->name('company.show');
	Route::post('company/store', 'Admin\CompanyController@store')->name('company.store');
	Route::post('company/edit', 'Admin\CompanyController@edit')->name('company.edit');
	Route::post('company/update', 'Admin\CompanyController@update')->name('company.update');
	Route::post('company/delete', 'Admin\CompanyController@destroy')->name('company.delete');

	// Employee Controller
	Route::get('employee', 'Admin\EmployeeController@index')->name('employee.index');
	Route::post('employee/show', 'Admin\EmployeeController@show')->name('employee.show');
	Route::post('employee/store', 'Admin\EmployeeController@store')->name('employee.store');
	Route::post('employee/edit', 'Admin\EmployeeController@edit')->name('employee.edit');
	Route::post('employee/update', 'Admin\EmployeeController@update')->name('employee.update');
	Route::post('employee/delete', 'Admin\EmployeeController@destroy')->name('employee.delete');

});

