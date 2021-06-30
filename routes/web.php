<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('company', 'Admin\CompanyController@index')->name('company.index');
Route::post('company/show', 'Admin\CompanyController@show')->name('company.show');
Route::post('company/store', 'Admin\CompanyController@store')->name('company.store');
Route::post('company/edit', 'Admin\CompanyController@edit')->name('company.edit');
Route::post('company/update', 'Admin\CompanyController@update')->name('company.update');
Route::post('company/delete', 'Admin\CompanyController@destroy')->name('company.delete');

