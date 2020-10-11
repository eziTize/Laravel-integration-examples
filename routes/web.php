<?php

use Illuminate\Support\Facades\Route;

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
    return view('landing');
});


// Twilio Integration
 Route::get('/twilio', function () {
    return view('twilio.start_session');
})->name('twilio.home');
Route::get('twilio-index', "TwilioController@index")->name('twilio.index');
Route::prefix('twilio')->group(function() {
   Route::get('join/{roomName}', 'TwilioController@joinRoom')->name('tw.session.connect');
   Route::post('details', 'TwilioController@createRoom')->name('tw.session.create');
});


// Stripe Integration
Route::get('stripe', 'StripeController@index')->name('stripe.home');
Route::post('stripe/store', 'StripeController@store')->name('stripe.store');


//Transferwise Integration
Route::get('profile', 'TransferwiseController@getProfile');
Route::get('transferwise', 'TransferwiseController@create')->name('transferwise.create');
Route::post('transferwise/store', 'TransferwiseController@store')->name('transferwise.store');