<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
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

// Route::get('/hash', function(){
// 	return bcrypt('123123123');;
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', 'Admin\LoginController@index')->name('admin_login');

Route::post('/admin/login', 'Admin\LoginController@login')->name('logging_in');
Route::post('/register', 'RegisterController@create')->name('new_reg');

Route::get('activation/{token}', 'RegisterController@userActivation');

Route::group(['prefix' => 'admin','middleware' => 'admin', 'namespace' => 'Admin'], function(){
	
	Route::get('/dashboard', 'LoginController@dashboard')->middleware('admin')->name('dashboard');
	Route::get('/file/{filename}/{folder}', 'FileController@view_file');
	
	Route::post('/logout', 'LoginController@logout')->name('logging_out');
	Route::post('/comment_query/{id}', 'CommentController@comment_query');
	Route::post('/comment_doc/{id}', 'CommentController@comment_doc');
	Route::post('/order/{id}', 'OrderController@create_order');

	Route::patch('/comment_query/{id}', 'CommentController@update_query_comment');
	Route::patch('/comment_doc/{id}', 'CommentController@update_doc_comment');
	Route::patch('/order/{id}', 'OrderController@update_order');
	Route::patch('/query_status/{id}', 'StatusController@query_status');
	Route::patch('/doc_status/{id}', 'StatusController@doc_status');

	Route::delete('/comment/{id}/{type}', 'CommentController@delete');
	Route::delete('/order/{id}', 'OrderController@delete');

});


Route::group(['middleware' => 'auth'], function(){

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/panel', 'HomeController@panel')->name('panel');
	Route::get('/file/{filename}/{folder}', 'Admin\FileController@view_file');

	Route::post('/request', 'QueryController@send_query');
	Route::post('/docs', 'DocsController@send_doc');
	
	Route::patch('/request/{id}', 'QueryController@update_query');
	Route::patch('/docs/{id}', 'DocsController@update_doc');
	Route::patch('/query_status/{id}', 'Admin\StatusController@query_status');
	Route::patch('/doc_status/{id}', 'Admin\StatusController@doc_status');
});


