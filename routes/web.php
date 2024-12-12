<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

######============ HOME ROUTE ============#########################
Route::controller(HomeController::class)->group(function () {
    Route::get('/', "index")->name("index");
});

######============ USERS ROUTE ============#########################
Route::controller(HomeController::class)->group(function () {
    Route::post('/subscribe', "Subscribe")->name("subscribe");
    Route::match(["POST","GET"],'/abonnement', "Abonnement")->name("abonnement");
});
