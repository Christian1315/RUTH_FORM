<?php

use App\Http\Controllers\Api\V1\HouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

###___
Route::prefix('v1')->group(function () {
    Route::prefix('immo')->group(function () {
        Route::prefix("house")->group(function () {
            Route::controller(HouseController::class)->group(function () {
                Route::any('{id}/retrieve', 'RetrieveHouse'); #RECUPERATION D'UNE MAISON
            });
        });
        ##___
    });
});
