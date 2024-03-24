<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class UserCommand extends Command {

  protected $name = "user";

  protected $description = "Указать телефон пользователя системы";

  public function handle($arguments = null) {
    $update = $this->getUpdate();
    //logs()->debug($update);
    if (empty($update['message']))
      return;
    if (count($update->message->entities) == 1){
      $this->replyWithMessage(['text' => 'Не указан телефон пользователя']); 
      $this->replyWithChatAction(['action' => Actions::TYPING]);
      return; 
    }
    $login = substr($update->message->text, $update->message->entities[1]['offset'], $update->message->entities[1]['length'] );
    $chat_id = $update->message->chat->id;
    $user = \App\Models\User::wherePhone($login)->first();
    if ($user == null){
      $this->replyWithMessage(['text' => 'Пользователя с телефоном: '.$login.' нет в системе']);  
      $this->replyWithChatAction(['action' => Actions::TYPING]);
      return; 
    }
    $user->telegram_chat_id = $chat_id;
    $user->save();
    $this->replyWithMessage(['text' => 'Номер чата закреплен за вами']);
    $this->replyWithChatAction(['action' => Actions::TYPING]);
  }
}
