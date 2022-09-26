<?php

use App\Http\Controllers\VehicleController;
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

Route::group(['middleware' => 'NoFuelCenterIsAllowed',
], static function ($router) {

    Route::group(['prefix' => 'vehicles'
    ], static function ($router) {
        Route::post('', [VehicleController::class, 'store'])->middleware("NoUserIsAllowed");
        Route::get('qr', [VehicleController::class, 'qr']);
    });

});
