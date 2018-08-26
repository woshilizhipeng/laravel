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
Route::group(['middleware'=>['web']],function(){

	Route::get('/','Home\IndexController@index');
    Route::get('/cate/{cate_id}','Home\IndexController@cate');
    Route::get('/a/{art_id}','Home\IndexController@article');

    //Route::get('/', 'IndexController@index');

    Route::any('admin/login','Admin\LoginController@login');
    Route::get('admin/code','Admin\LoginController@code');
    //Route::get('admin/getcode','Admin\LoginController@getcode');





});

Route::group(['middleware'=>['web','admin.login'],'namespace'=>'Admin'],function(){


  

    Route::any('admin/index','IndexController@index');
    Route::any('admin/info','IndexController@info');
    Route::any('admin/quit','LoginController@quit');
    Route::any('admin/pass','IndexController@pass');

    Route::post('admin/cate/changeorder','CategoryController@changeOrder');
    Route::get('admin/category/create','CategoryController@create');



    Route::resource('admin/category','CategoryController');
    Route::resource('admin/article','ArticleController');

    Route::any('admin/upload','CommonController@upload');

    Route::resource('admin/links','LinksController');
    Route::post('admin/links/changeorder','LinksController@changeOrder');
    Route::resource('admin/navs','NavsController');
    Route::post('admin/navs/changeorder','NavsController@changeOrder');
    Route::get('admin/config/putfile','ConfigController@putFile');

    Route::resource('admin/config','ConfigController');
    
    Route::post('admin/config/changeorder','ConfigController@changeOrder');
    

    Route::post('admin/config/changecontent','ConfigController@changeContent');
    
    
    



});
