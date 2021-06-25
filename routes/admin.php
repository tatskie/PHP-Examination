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

	Route::get('company', 'Admin\CompanyController@index')->name('company.index');
	Route::post('company/show', 'Admin\CompanyController@show')->name('company.show');
	Route::post('company/store', 'Admin\CompanyController@store')->name('company.store');
	Route::post('company/edit', 'Admin\CompanyController@edit')->name('company.edit');
	Route::post('company/update', 'Admin\CompanyController@update')->name('company.update');
	Route::post('company/delete', 'Admin\CompanyController@destroy')->name('company.delete');
	// Route::get('{path}', 'Admin\DashboardController@index')->where('path','([A-z\d\-\/_.]+)?'); // for vue router
});

