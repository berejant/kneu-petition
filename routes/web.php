<?php

use Illuminate\Support\Facades\Route;
use Kneu\Petition\Http\Controllers\Auth\LoginController;
use Kneu\Petition\Http\Controllers\PetitionCommentController;
use Kneu\Petition\Http\Controllers\PetitionController;

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

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login/complete', [LoginController::class, 'loginComplete']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [PetitionController::class, 'index'])->name('petitions.index.open');
Route::get('/petitions/successful', [PetitionController::class, 'index'])->name('petitions.index.successful');
Route::post('/petitions/{petition}/vote', [PetitionController::class, 'vote'])->name('petitions.vote');
Route::resource('petitions', PetitionController::class);
Route::resource('petitions.comments', PetitionCommentController::class);

Route::model('comment', Kneu\Petition\PetitionComment::class);
