<?php

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

Route::get('/room/{roomKey}/tasks', function ($roomKey) {
    try {
        return Room::where('room_key', $roomKey)->firstOrFail()->tasks;
    } catch (\Throwable $th) {
        $room = Room::create([
            'room_key' => $roomKey,
        ]);

        $room->tasks()->create([
            'title' => 'Twoje Pierwsze Zadanie',
            'description' => 'Dodaj kolejne!',
        ]);

        return response($room->tasks, 201);
    }
})->middleware(['throttle:api-room'])->where('roomKey', '[\w-]{10,255}');

Route::post('/room/{roomKey}/tasks', function (Request $request, $roomKey) {
    $room = Room::where('room_key', $roomKey)->firstOrFail();

    $request->validate([
        'name' => 'required|max:120',
        'description' => 'nullable|max:600',
    ]);

    $input = $request->all();

    return $room->tasks()->create([
        'title' => $input['name'],
        'description' => $input['description'],
    ]);
});

Route::delete('/room/{roomKey}/tasks/{taskId}', function ($roomKey, $taskId) {
    $room = Room::where('room_key', $roomKey)->firstOrFail();

    $room->tasks()->findOrFail($taskId)->delete();
});

Route::put('/room/{roomKey}/tasks/{taskId}', function (Request $request, $roomKey, $taskId) {
    $room = Room::where('room_key', $roomKey)->firstOrFail();

    $input = $request->boolean('isCompleted');

    $task = $room->tasks()->findOrFail($taskId);
    $task->is_completed = $input;

    $task->save();
});
