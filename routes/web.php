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
Route::post('/threads', 'ThreadsController@store')->middleware('must-be-confirmed');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
/*Route::get('/threads','ThreadsController@index');
Route::get('') */
Route::resource('/threads', 'ThreadsController')->except(['show']);
// reply store... limit assess to one per minute
// Route::middleware('throttle:5')->post('/threads/{thread}/replies', 'RepliesController@store');
Route::post('/threads/{thread}/replies', 'RepliesController@store');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('reply.delete');
Route::patch('/replies/{reply}', 'RepliesController@update')->name('reply.update');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@store');
Route::delete('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@destroy');

//best reply.
Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//likes favorites..
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::get('profiles/{profile_user}', 'ProfilesController@show')->name('profile.show');
//user ...
Route::delete('/profiles/{profile_user}/notifications/{notificationId}', 'UserNotificationsController@destroy')->name('notifications.destroy');
Route::get('/profiles/{profile_user}/notifications', 'UserNotificationsController@index');
Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');
//APIs.....
Route::get('api/users', 'Api\UsersController@index')->name('users.api');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar.store')->middleware('auth');

// Testing soap.
Route::any('/soap/client', 'SoapClientController@index')->name('SoapClient.index');
Route::any('/soap/server', 'SoapServerController@index')->name('SoapServer.index');
Route::get('/soap/server/wsdl', 'SoapServerController@wsdl')->name('Soap.wsdl');
