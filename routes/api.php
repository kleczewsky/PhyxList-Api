<?php

use App\Http\Controllers\TaskController;
use App\Models\Room;
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

Route::get('/room/{roomKey}/tasks', [TaskController::class, 'index'])->middleware(['throttle:api-room'])->where('roomKey', '[\w-]{10,255}');

Route::post('/room/{roomKey}/tasks', [TaskController::class, 'store']);

Route::delete('/room/{roomKey}/tasks/{taskId}', [TaskController::class, 'destroy']);

Route::put('/room/{roomKey}/tasks/{taskId}', [TaskController::class, 'update']);

Route::get('/ping', function () {
    return 'pong';
});
