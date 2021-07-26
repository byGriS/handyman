<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller {
  public function index() {
    if (Auth::check())
      return view('app')->with('user', Auth::user());
    else
      return view('auth.login');
  }

  public function test() {
    $valid = User::where('phone', '9876543210')->first();
    $valid->password = Hash::make('123123');
    $valid->save();
    dd(count($valid));
    $tasks = Work::find(1)->tasks;
    dd($tasks[0]->user);
  }

  public function telegram_message_webhook() {
    $telegram = new \Telegram\Bot\Api(config('telegram.bots.mybot.token'));
    $telegram->addCommand(\App\Telegram\Commands\StartCommand::class);
    $telegram->addCommand(\App\Telegram\Commands\UserCommand::class);
    $commandsHandler = $telegram->commandsHandler(true);
  }

  public function telegram_notification() {
    $telegram = new \Telegram\Bot\Api(config('telegram.bots.mybot.token'));
    $users = User::where('telegram_chat_id', '!=', null)->get();
    $works = Work::whereStatus(1)->get();
    foreach ($works as $work) {
      $work = Work::find(22);
      foreach ($work->tasks as $task) {
        $note = $task->notes()->orderBy('dt', 'desc')->first();
        $text = $work->name . PHP_EOL . 'Не указаны данные по работе';
        if ($note->dt != date("Y-m-d")) {
          foreach ($users as $user) {
            $telegram->setAsyncRequest(true)->sendMessage(['chat_id' => $user->telegram_chat_id, 'text' => $text]);
          }
        } else {
          $consumptionStandart = $note->people * $task->standartPeople * $task->standartConsumption;
          if ($consumptionStandart < $note->consumption) {
            $text = $work->name . PHP_EOL . 'Не выполнен норматив по раствору' . PHP_EOL . 'Кол-во человек:' . $note->people . ', Кол-во раствора:' . $note->consumption . ', Кол-во раствора по нормативу:' . $consumptionStandart;
            foreach ($users as $user) {
              $telegram->setAsyncRequest(true)->sendMessage(['chat_id' => $user->telegram_chat_id, 'text' => $text]);
            }
          }
        }
      }
    }

    /*$telegram = new \Telegram\Bot\Api(config('telegram.bots.mybot.token'));
    $text = 'Не выполнен норматив' . PHP_EOL .
      'Кол-во раствора по нормативу:' . $claim->location_entrance . ', использовано:' . $claim->location_floor;
    $chats = TelegramChats::all();
    foreach ($chats as $chat) {
      $telegram->setAsyncRequest(true)->sendMessage(['chat_id' => $chat->chat_id, 'text' => $text]);
    }*/
  }
}
