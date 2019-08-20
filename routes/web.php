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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profiles/{user}', 'ProfileController@index')->name('user.profile');

Route::resource('threads', 'ThreadsController', [
    'names' => [
        'index'   => 'thread.index',
        'create'  => 'thread.create',
        'store'   => 'thread.store',
        'update'  => 'thread.update',
        'destroy' => 'thread.destroy'
    ]
])->except(['edit', 'show']);

// Temporary location(when i put this route with the replies routes it shows me `Page Not Found`---> TODO)
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index')->name('reply.index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->name('reply.store');

Route::get('/threads/{channel}', 'ThreadsController@index')->name('channel.index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('thread.show');
Route::get('/threads/{channel}/{thread}#replies', function(){return redirect('threads/{channel}/{thread}#replies');})->name('thread.showReplies');
Route::get('/threads/{channel}/{thread}#reply-{reply}', function($channel, $thread, $reply){return redirect('threads/{channel}/{thread}#reply-' . $reply);})->name('thread.showReply');

Route::resource('threads.replies', 'RepliesController', [
    'names' => [
        'update'  => 'reply.update',
        'destroy' => 'reply.destroy'
    ]
])->only([ 'destroy','update']);


Route::post('/replies/{reply}/favorite', 'FavoritesController@store')->name('reply.favorite');
Route::delete('/replies/{reply}/favorite', 'FavoritesController@destroy')->name('reply.unFavorite');

Route::post('/threads/{channel}/{thread}/subscription', 'SubscriptionsController@store')->name('thread.subscribe');
Route::delete('/threads/{channel}/{thread}/subscription', 'SubscriptionsController@destroy')->name('thread.unSubscribe');


Route::get('/{user}/notifications', 'NotificationsController@index')->name('notification.index');
Route::delete('/{user}/notifications/{notification}', 'NotificationsController@destroy')->name('notification.destroy');
