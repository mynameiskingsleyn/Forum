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
/*
View::composer('stats', function($view){
    $view->with('stats',app('App\Stats'));
});
*/
Route::get('/', function () {
    return view('welcome');
});
//Route::get('/threads','ThreadController@index');
Route::post('/threads', 'ThreadsController@store');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
/*Route::get('/threads','ThreadsController@index');
Route::get('') */
Route::resource('/threads', 'ThreadsController')->except(['show']);
Route::post('/threads/{thread}/replies', 'RepliesController@store');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('reply.delete');
Route::patch('/replies/{reply}', 'RepliesController@update')->name('reply.update');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//likes favorites..
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::get('profiles/{profile_user}', 'ProfilesController@show')->name('profile.show');
