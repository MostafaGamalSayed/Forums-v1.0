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
Route::get('/register/activate', 'Auth\ActivationController@index')->name('register.activate');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profiles/{user}', 'ProfileController@index')->name('user.profile');

Route::resource('threads', 'ThreadsController', [
    'names' => [
        'index'   => 'thread.index',
        'create'  => 'thread.create',
        'store'   => 'thread.store',
        'update'  => 'thread.update',
        'destroy' => 'thread.destroy'
    ],
])->except(['edit', 'show']);

Route::get('threads/trending', 'ThreadsController@trending')->name('thread.trending');
Route::get('threads/search', 'SearchController@show')->name('thread.search');

// Temporary location(when i put this route with the replies routes it shows me `Page Not Found`---> TODO)
Route::get('{channel}/threads/{thread}/replies', 'RepliesController@index')->name('reply.index');
Route::post('{channel}/threads/{thread}/replies', 'RepliesController@store')->name('reply.store');

Route::get('{channel}/threads', 'ThreadsController@index')->name('channel.index');
Route::get('{channel}/threads/{thread}', 'ThreadsController@show')->name('thread.show');
Route::get('{channel}/threads/{thread}#replies', function(){return redirect('{channel}/threads/{thread}#replies');})->name('thread.showReplies');
Route::get('{channel}/threads/{thread}#reply-{reply}', function($channel, $thread, $reply){return redirect('{channel}/threads/{thread}#reply-' . $reply);})->name('thread.showReply');

Route::resource('threads.replies', 'RepliesController', [
    'names' => [
        'update'  => 'reply.update',
        'destroy' => 'reply.destroy'
    ]
])->only([ 'destroy','update']);
Route::post('/replies/{reply}/favorite', 'FavoritesController@store')->name('reply.favorite');
Route::delete('/replies/{reply}/favorite', 'FavoritesController@destroy')->name('reply.unFavorite');
Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('reply.best');

Route::post('{channel}/threads/{thread}/subscription', 'SubscriptionsController@store')->name('thread.subscribe');
Route::delete('{channel}/threads/{thread}/subscription', 'SubscriptionsController@destroy')->name('thread.unSubscribe');


Route::get('/{user}/notifications', 'NotificationsController@index')->name('notification.index');
Route::delete('/{user}/notifications/{notification}', 'NotificationsController@destroy')->name('notification.destroy');


Route::post('users/{user}/avatar', 'Api\UsersAvatarController@store')->name('avatar.upload');


Route::get('/search', function(){return view('search');});


Route::view('test', 'test');
