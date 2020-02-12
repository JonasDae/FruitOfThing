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
Route::get('/home', 'HomeController@index');
Route::get('/graph', 'GraphController@index')->name('graph.index');
Route::get('/table', 'TableController@index')->name('table.index');

Route::get('/modules', 'ModuleController@index')->name('modules.index');
Route::patch('/modules', 'ModuleController@update')->name('modules.update');
Route::delete('/modules/{module}', 'ModuleController@destroy')->name('modules.destroy');

Route::get('/fields', 'FieldController@index')->name('fields.index');
Route::post('/fields', 'FieldController@store')->name('fields.store');
Route::patch('/fields', 'FieldController@update')->name('fields.update');
Route::delete('/fields/{field}', 'FieldController@destroy')->name('fields.destroy');

Route::get('/fruit_types', 'Fruit_typeController@index')->name('fruits.index');
Route::post('/fruit_types', 'Fruit_typeController@store')->name('fruits.store');
Route::patch('/fruit_types', 'Fruit_typeController@update')->name('fruits.update');
Route::delete('/fruit_types/{fruit_type}', 'Fruit_typeController@destroy')->name('fruits.destroy');

Route::get('/sensors', 'SensorController@index')->name('sensors.index');

Route::get('/sensor_types', 'Sensor_typeController@index')->name('sensor_types.index');
Route::patch('/sensor_types', 'Sensor_typeController@update')->name('sensor_types.update');
Route::post('/sensor_types', 'Sensor_typeController@store')->name('sensor_types.store');
Route::delete('/sensor_types/{sensor_type}', 'Sensor_typeController@destroy')->name('sensor_types.destroy');

Route::get('/profile', 'ProfileController@index')->name('profile.index');
Route::patch('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/notification/markasread', function () {
    auth()->user()->unreadNotifications->markAsRead();
})->name('notification.markasread');
Route::get('/notification/delete/{id}', function ($id) {
    auth()->user()->notifications()->where('id', $id)->get()->first()->delete();
    return back();
})->name('notification.destroy');
