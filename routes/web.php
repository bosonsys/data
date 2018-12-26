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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );


//company //
Route::get('/company', 'CompanyController@index')->name('company');
Route::post('/selectCompany', 'CompanyController@searchCompany')->name('selectCompany');
Route::get('/user_comp/{id}', 'CompanyController@delComp')->name('company.del');
Route::post('/company/create', 'CompanyController@create')->name('company.create');
Route::post('/getcompany', 'CompanyController@getCompany')->name('company.getcompany');
Route::get('/search', 'CompanyController@searchCompany')->name('company.search');
Route::post('/get_usercompany', 'CompanyController@getUserCompany');