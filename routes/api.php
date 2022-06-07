<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureBearer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|
| Authorization: Register, Login
| 
*/
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');

/*
|
| Airports: Search
| 
*/
Route::get('/airport', 'AirportController@search');

/*
|
| Flights: Search
| 
*/
Route::get('/flight', 'FlightController@search');

/*
|
| Bookings
| 
*/
Route::post('/booking', 'BookingController@create');
Route::get('/booking/{code}', 'BookingController@get');

/*
|
| Seats
| 
*/
Route::patch('/booking/{code}/seat', 'PassengerController@change');
Route::get('/booking/{code}/seat', 'PassengerController@get');

/*
|
| User
| 
*/
Route::get('/user', 'UserController@profile')
    ->middleware(EnsureBearer::class);
Route::get('/user/booking', 'UserController@bookings')
    ->middleware(EnsureBearer::class);