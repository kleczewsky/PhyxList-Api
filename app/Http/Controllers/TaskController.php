<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Room;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($roomKey)
    {
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $roomKey)
    {
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roomKey, $taskId)
    {
        $room = Room::where('room_key', $roomKey)->firstOrFail();

        $input = $request->boolean('isCompleted');

        $task = $room->tasks()->findOrFail($taskId);
        $task->is_completed = $input;

        $task->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($roomKey, $taskId)
    {
        $room = Room::where('room_key', $roomKey)->firstOrFail();

        $room->tasks()->findOrFail($taskId)->delete();
    }
}
