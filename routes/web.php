<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;

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
//route login
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'process']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// route dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/', [DashboardController::class, 'index'])->middleware('auth');

//route barang
// Route::resource('/barang', BarangController::class)->middleware('auth');
// Route::resource('/karyawan', KaryawanController::class)->middleware('auth');
// Route::resource('/units', UnitController::class)->middleware('auth');
// Route::resource('/jabatans', JabatanController::class)->middleware('auth');
Route::resource('/data', DataController::class)->middleware('auth');
Route::resource('/book', BookController::class)->middleware('auth');
Route::patch('/data/update-status/{id}', [DataController::class, 'updateStatus'])->name('data.updateStatus');

