<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\IDCardController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;

use App\Models\Vehicle;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace'=>'App\Http\Controllers'], function(){
    Route::apiResource('users', UserController::class);
    Route::apiResource('bookings', BookingController::class);
    Route::apiResource('rides', RideController::class);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('reviews', ReviewController::class);
});

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout']);



Route::get('/testing',function(){
    $vehicle = Vehicle::find(1);
    return view('welcome',['vehicle'=>$vehicle]);
} );

Route::post('users/{id}/image', [UserController::class,'setUserImage']);
Route::post('users/{id}/license/recto', [UserController::class,'setLicenseImageRecto']);
Route::post('users/{id}/license/verso', [UserController::class,'setLicenseImageVerso']);
Route::post('users/{id}/idcard/recto', [UserController::class,'setIdCardImageRecto']);
Route::post('users/{id}/idcard/verso', [UserController::class,'setidCardImageVerso']);


Route::post('vehicles/setVehicleImage/{id}', [VehicleController::class,'setVehicleImage']);
Route::get('vehicles/{id}/user', [VehicleController::class,'getUser']);


Route::post('stripe', [PaymentController::class,'stripePost']);
Route::post('account/', [PaymentController::class,'account']);
Route::delete('account/{id}', [PaymentController::class,'deleteUserAccount']);
Route::post('payments', [PaymentController::class,'createPayment']);
Route::post('price', [PaymentController::class,'calculatePrice']);




