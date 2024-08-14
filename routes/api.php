<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AuthController;

// Route::get('/login', function(){
//     return 'test login';
// });
// Rute autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('unauthorized', function () {
    return response()->json(['error' => 'Unauthorized.'], 401);
})->name('unauthorized');

// Middleware untuk melindungi route dengan JWT
Route::middleware('auth:api')->group(function () {
    // Rute untuk checklist
    Route::post('/checklists', [TodoController::class, 'createChecklist']);
    Route::delete('/checklists/{id}', [TodoController::class, 'deleteChecklist']);
    Route::get('/checklists', [TodoController::class, 'getChecklists']);
    Route::get('/checklists/{id}', [TodoController::class, 'getChecklistDetails']);
    Route::post('/checklists/{id}/items', [TodoController::class, 'createTodoItem']);

    // Rute untuk item to-do
    Route::get('/items/{id}', [TodoController::class, 'getTodoItem']);
    Route::put('/items/{id}', [TodoController::class, 'updateTodoItem']);
    Route::patch('/items/{id}/status', [TodoController::class, 'updateTodoItemStatus']);
    Route::delete('/items/{id}', [TodoController::class, 'deleteTodoItem']);
});
