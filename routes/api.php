<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomestayController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("register",[UserController::class,'register']);
Route::post("login",[UserController::class,'index']);
Route::get("countries",[UserController::class,'getCountries']);
Route::post("addhomestay",[HomestayController::class,'addHomestay']);
Route::get("gethomestay",[HomestayController::class,'getHomestay']);
Route::get("cities",[UserController::class,'getCities']);
Route::post("city",[UserController::class,'getCityById']);
Route::post("bookHomestay",[HomestayController::class,'bookHomestay']);
Route::post("getHomestayById",[HomestayController::class,'getHomestayById']);
Route::post("getBookingsById",[HomestayController::class,'getBookingsById']);
Route::post("cancelBooking",[HomestayController::class,'cancelBooking']);
Route::post("getBookingsProvider",[HomestayController::class,'getBookingsProvider']);
Route::post('bookingDone',[HomestayController::class, 'bookingDone']);
Route::post('searchHomestay',[HomestayController::class, 'searchHomestay']);
Route::post('logout',[UserController::class, 'logout']);
Route::post('ratings',[HomestayController::class, 'ratings']);
Route::post('resetPassword',[UserController::class, 'resetPassword']);
Route::post('deleteHomestay',[HomestayController::class, 'deleteHomestay']);
Route::post('editHomestay',[HomestayController::class, 'editHomestay']);
Route::post('updateBooking',[HomestayController::class, 'updateBooking']);
