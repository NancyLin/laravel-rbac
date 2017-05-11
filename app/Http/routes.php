<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//认证路由
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

//注册路由
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::group(['middleware' => ['auth']], function(){
	Route::get('home', 'HomeController@index');
	Route::controller('check', 'CheckController');
	Route::controller('load', 'LoadBaseDataController');

	Route::get('user', 'Rbac\UserController@index');
	Route::controller('user', 'Rbac\UserController');

	Route::get('role', 'Rbac\RoleController@index');
	Route::controller('role', 'Rbac\RoleController');

	Route::get('permission', 'Rbac\PermissionController@index');
	Route::controller('permission', 'Rbac\PermissionController');
});
