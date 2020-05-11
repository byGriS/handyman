<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiNoteController extends ApiController
{
  public function add(Request $request)
  {
    $task = Task::find($request->task_id);
    $note = new Note();
    $note->people = $request->people;
    $note->consumption = $request->consumption;
    $note->dt = Carbon::parse($request->dt)->toDateString();
    $note->task()->associate($task);
    $note->save();
    return $note->id;
  }

  public function changeNotePeople(Request $request)
  {
    $note = Note::find($request->id);
    $note->people = $request->people;
    $note->save();
  }

  public function changeNoteConsumption(Request $request)
  {
    $note = Note::find($request->id);
    $note->consumption = $request->consumption;
    $note->save();
  }
}
