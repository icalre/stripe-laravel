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
    return redirect()->route('login');
});

Route::post('/subscriptions', 'SubscriptionController@store')->name('subscription.store');
Route::delete('/subscriptions', 'SubscriptionController@destroy')->name('subscription.delete');
Route::patch('/subscriptions', 'SubscriptionController@update')->name('subscription.update');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/videos', 'VideosController@videos')->name('home');

Route::post('/stripe/webhook', 'WebhooksController@handle')->name('stripe.webhooks');
