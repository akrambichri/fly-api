<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController as AuthController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TrottineteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user-profile', [AuthController::class, 'userProfile']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/refresh', [AuthController::class, 'refresh']);

Route::resource('/addresses', AddressController::class);
Route::resource('/billings', BillingController::class);

Route::put('/clients/billings', [ClientController::class, 'addCard']);
Route::post('/clients/billings', [ClientController::class, 'chargeCard']);
Route::get('/clients/billings/invoices', [ClientController::class, 'getAllInvoices']);

Route::resource('/clients', ClientController::class);




Route::resource('/plans', PlanController::class);
Route::resource('/reservations', ReservationController::class);
Route::resource('/transactions', TransactionController::class);
Route::resource('/trottinetes', TrottineteController::class);

Route::post('/trottinetes/verifyQr', [TrottineteController::class, 'verifyQr']);
