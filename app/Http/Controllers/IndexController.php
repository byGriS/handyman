<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
  public function index()
  {
    if (Auth::check())
      return view('app')->with('user', Auth::user());
    else
      return view('auth.login');
  }

  public function test(){
    $valid = User::where('phone', '9876543210')->first();
    $valid->password = Hash::make('123123');
    $valid->save();
    dd(count($valid));
    $tasks = Work::find(1)->tasks;
    dd($tasks[0]->user);
  }

  public function telegram_message_webhook(){
    $telegram = new \Telegram\Bot\Api(config('telegram.bots.mybot.token'));
    $telegram->addCommand(\App\Telegram\Commands\StartCommand::class);
    $commandsHandler = $telegram->commandsHandler(true);    
  }
}
