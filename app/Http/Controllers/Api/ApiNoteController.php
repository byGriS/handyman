<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiNoteController extends ApiController
{
    public function add(Request $request){
      $task = Task::find($request->task_id);
      $note = new Note();
      $note->people = $request->people;
      $note->consumption = $request->consumption;
      $note->dt = Carbon::now()->toDateString();
      $note->task()->associate($task);
      $note->save();
      return "1";
    }

}
