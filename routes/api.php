<?php

use Illuminate\Http\Request;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

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

// Middleware untuk melindungi route dengan Passport
Route::middleware('auth:api')->group(function () {
    Route::patch('/data/update-status/{id}', [DataController::class, 'updateStatus'])->name('data.updateStatus');
});
