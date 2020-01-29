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

Route::get('/', 'HomeController@index')->name('home'); //give route a name so it can be addressed from a view
Route::redirect('/home', '/');

Route::get('/home/chart_build/{fruittype}/{date_display}', 'HomeController@chart_build')->name('home.chart_build');

Route::get('/modules', 'ModuleController@index')->name('modules.index');
Route::get('/modules/create', 'ModuleController@create')->name('modules.create');
Route::get('/modules/store', 'ModuleController@store')->name('modules.store');

Route::get('/fields', 'FieldController@index')->name('fields');

Route::get('/fruit_types', 'Fruit_typeController@index')->name('fruits');

Route::get('/sensors', 'SensorController@index')->name('sensors');

Route::get('/profile', 'ProfileController@index')->name('profile.index');
Route::patch('/profile', 'ProfileController@update')->name('profile.update');
