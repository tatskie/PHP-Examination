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
	Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

	// Company Controller
	Route::resource('company', 'Admin\CompanyController')->except(['create', 'show', 'update']);
	Route::post('company/show', 'Admin\CompanyController@show')->name('company.show');
	Route::post('company/update/{company}', 'Admin\CompanyController@update')->name('company.update');

	// Employee Controller
	Route::resource('employee', 'Admin\EmployeeController')->except(['create', 'show']);
	Route::post('employee/show', 'Admin\EmployeeController@show')->name('employee.show');

});

