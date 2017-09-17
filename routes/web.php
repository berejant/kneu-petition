<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/login', 'Auth\LoginController@login')->name('login');
Route::get('/login/complete', 'Auth\LoginController@loginComplete');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'PetitionController@index')->name('petitions.index.open');
Route::get('/petitions/successful', 'PetitionController@index')->name('petitions.index.successful');
Route::post('/petitions/{petition}/vote', 'PetitionController@vote')->name('petitions.vote');
Route::resource('petitions', 'PetitionController');
Route::resource('petitions.comments', 'PetitionCommentController');

Route::model('comment', Kneu\Petition\PetitionComment::class);

