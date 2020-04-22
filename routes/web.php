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

Route::get('/', function() {
    return view('welcome');
});

Route::get('/contact', 'HomeController@contact')->name('contact');
Route::get('/secret', 'HomeController@secret')
    ->name('secret')
    ->middleware('can:secret.page');

Route::patch('/posts/{id}/restore', 'PostController@restore');
Route::delete('/posts/{id}/forcedelete', 'PostController@forcedelete');
Route::get('/posts/archive', 'PostController@archive');

Route::get('/posts/all', 'PostController@all');
Route::resource('/posts', 'PostController');

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

