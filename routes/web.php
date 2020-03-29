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

Route::view('/','welcome');

// Rotas de los estados

Route::get('statuses', 'StatusesController@index')->name('statuses.index');
Route::post('statuses','StatusesController@store')->name('statuses.store')->middleware('auth');

// Rutas de los likes
Route::post('statuses/{status}/likes','StatusLikesController@store')->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes','StatusLikesController@destroy')->name('statuses.likes.destroy')->middleware('auth');

// Rutas de los estados
Route::post('statuses/{status}/comments','StatusCommentsController@store')->name('statuses.comments.store')->middleware('auth');

//Rutas para los likes de los comentarios
Route::post('comments/{comment}/likes','CommentLikesController@store')->name('comments.likes.store')->middleware('auth');
Route::delete('comments/{comment}/likes','CommentLikesController@destroy')->name('comments.likes.destroy')->middleware('auth');


// Users Routes
Route::get('@{user}','UsersController@show')->name('users.show');

// User statuses routes
Route::get('users/{user}/statuses','UsersStatusController@index')->name('users.statuses.index');

//Friendships routes
Route::post('friendships/{recipient}', 'FriendshipsController@store')->name('friendships.store')->middleware('auth');
Route::delete('friendships/{user}', 'FriendshipsController@destroy')->name('friendships.destroy')->middleware('auth');

// Accept Friendship Routes
Route::get('accept-friendships', 'AcceptFriendshipsController@index')->name('accept-friendships.index');
Route::post('accept-friendships/{sender}', 'AcceptFriendshipsController@store')->name('accept-friendships.store')->middleware('auth');
Route::delete('accept-friendships/{sender}', 'AcceptFriendshipsController@destroy')->name('accept-friendships.destroy')->middleware('auth');

Route::auth();
