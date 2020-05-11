<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiWorkController extends ApiController
{
  public function getListWorks()
  {
    $works = Work::where('status', 1)->get();
    return $works;
  }

  public function getListTasks(Request $request)
  {
    $tasks = Work::find($request->work_id)->tasks()->where('status', 1)->get();
    foreach ($tasks as $task) {
      $task->work = $task->work;
      $task->handyman = $task->user;
      $task->notes = $task->notes;
    }
    return $tasks;
  }

  public function getListTasksByUser()
  {
    $tasks = $this->user->tasks()->where('status', 1)->get();
    foreach ($tasks as $task) {
      $task->work = $task->work;
      $task->handyman = $task->user;
      $task->notes = $task->notes;
    }
    return $tasks;
  }

  public function changeTaskCapacity(Request $request)
  {
    $task = Task::find($request->task_id);
    $task->capacity = $request->capacity;
    $task->save();
  }

  public function changeTaskEnd(Request $request)
  {
    $task = Task::find($request->task_id);
    $task->end = Carbon::parse($request->end)->toDateString();
    $task->save();
  }

  public function changeTaskStandartPeople(Request $request)
  {
    $task = Task::find($request->task_id);
    $task->standartPeople = $request->standartPeople;
    $task->save();
  }

  public function changeTaskStandartConsumption(Request $request)
  {
    $task = Task::find($request->task_id);
    $task->standartConsumption = $request->standartConsumption;
    $task->save();
  }

  public function addTask(Request $request)
  {
    $handyman = User::find($request->newTask['handyman']);
    $work = Work::find($request->newTask['workId']);
    $task = new Task;
    $task->typeWork = $request->newTask['type'];
    $task->capacity = $request->newTask['capacity'];
    $task->end = Carbon::parse($request->newTask['end'])->toDateString();
    $task->standartPeople = $request->newTask['standartPeople'];
    $task->standartConsumption = $request->newTask['standartConsumption'];
    $task->holiday = 0;
    $task->status = 1;
    $task->user()->associate($handyman);
    $task->work()->associate($work);
    $task->save();
  }
  
  public function deleteTask(Request $request){
    $task = Task::find($request->id);
    $task->status = 3;
    $task->save();
  }

  public function addWork(Request $request){
    $work = new Work;
    $work->name = $request->title;
    $work->status = 1;
    $work->save();
  }

  public function deleteWork(Request $request){
    $work = Work::find($request->id);
    $work->status = 3;
    $work->save();
  }

  public function completeTask(Request $request){
    $task = Task::find($request->id);
    $task->status = 2;
    $task->save();
  }
  
  public function completeWork(Request $request){
    $work = Work::find($request->id);
    $work->status = 2;
    $work->save();
  }
}
