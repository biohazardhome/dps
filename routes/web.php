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

Route::group(['as' => 'placemark.', 'prefix' => 'placemark'], function() {
	Route::get('/', 'PlacemarkController@index')->name('index');
	Route::any('/all', 'PlacemarkApiController@all')->name('all');
	Route::any('/find/{id}', 'PlacemarkApiController@find')->name('find');
	Route::get('/create', 'PlacemarkController@create')/*->middleware('can:create,App\Placemark')*/->name('create');
	Route::post('/store', 'PlacemarkController@store')->name('store');
	Route::post('/update/{id}', 'PlacemarkController@update')->name('update');
	Route::post('/update-coords/{id}', 'PlacemarkController@updateCoords')->name('update-coords');
});

Route::group(['as' => 'placemark-type.', 'prefix' => 'placemark-type'], function() {
	Route::get('/', 'PlacemarkTypeController@index')->name('index');
	Route::any('/all', 'PlacemarkTypeController@all')->name('all');
	Route::get('/create', 'PlacemarkTypeController@create')->name('create');
	Route::post('/store', 'PlacemarkTypeController@store')->name('store');
});

Route::group(['as' => 'user.', 'prefix' => 'user'], function() {
	Route::post('auth', 'UserController@auth')->name('auth');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
