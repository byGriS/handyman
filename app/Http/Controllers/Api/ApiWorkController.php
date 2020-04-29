<?php

namespace App\Http\Controllers\Api;

use App\Models\Work;
use Illuminate\Http\Request;

class ApiWorkController extends ApiController
{
    public function getListWorks(){
      $works = Work::all();
      return $works;
    }

    public function getListTasks(Request $request){
      $tasks = Work::find($request->work_id)->tasks;
      foreach ($tasks as $task) {
        $task->work = $task->work;
        $task->handyman = $task->user;     
        $task->notes = $task->notes;
      }
      return $tasks;
    }

    public function getListTasksByUser(){
      $tasks = $this->user->tasks()->where('status', 1)->get();
      foreach($tasks as $task){
        $task->work = $task->work;
        $task->handyman = $task->user;        
        $task->notes = $task->notes;
      }
      return $tasks;
    }
}
