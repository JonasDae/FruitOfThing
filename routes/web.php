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
Route::patch('/modules', 'ModuleController@update')->name('modules.update');
Route::delete('/modules/{module}', 'ModuleController@destroy')->name('modules.destroy');

Route::get('/fields', 'FieldController@index')->name('fields.index');
Route::get('/fields/create', 'FieldController@create')->name('fields.create');

Route::get('/fruit_types', 'Fruit_typeController@index')->name('fruits.index');
Route::get('/fruit_types/create', 'Fruit_typeController@create')->name('fruits.create');
Route::post('/fruit_types', 'Fruit_typeController@store')->name('fruits.store');
Route::patch('/fruit_types', 'Fruit_typeController@update')->name('fruits.update');
Route::delete('/fruit_types/{fruit_type}', 'Fruit_typeController@destroy')->name('fruits.destroy');

Route::get('/sensors', 'SensorController@index')->name('sensors.index');
Route::patch('/sensors', 'SensorController@update')->name('sensors.update');
Route::delete('/sensors/{sensor}', 'SensorController@destroy')->name('sensors.destroy');


Route::get('/profile', 'ProfileController@index')->name('profile.index');
Route::patch('/profile', 'ProfileController@update')->name('profile.update');
