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

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home'); //give route a name so it can be addressed from a view

Route::get('/modules', 'ModuleController@index')->name('modules');

Route::get('/fields', 'FieldController@index')->name('fields');

Route::get('/sensors', 'SensorController@index')->name('sensors');

Route::get('/profile', 'ProfileController@index')->name('profile');
