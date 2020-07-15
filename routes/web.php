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

Route::get('/', 'CustomerController@index');
Route::get('/customers', 'CustomerController@customers')->name('customers.list');
Route::post('/add-customer', 'CustomerController@addCustomer')->name('customer.add');
Route::get('/edit-customer', 'CustomerController@editCustomer')->name('customer.edit');
Route::get('/delete-customer', 'CustomerController@deleteCustomer')->name('customer.delete');


Route::get('/export-customers', 'CustomerController@exportCustomers')->name('export.customers');
Route::get('/view-pdf/{id}', 'CustomerController@viewPDF');