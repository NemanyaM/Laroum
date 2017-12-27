<?php


Route::get('/', function () {
    return view('welcome');
});


Route::post('/threads/{thread}/replies', 'RepliesController@store');
Route::resource('/threads', 'ThreadsController');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
