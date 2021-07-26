<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command {

  protected $name = "start";

  protected $description = "Команда инициализации чата";

  public function handle() {
    $this->replyWithMessage(['text' => 'Вы подписаны на уведомления']);
    $this->replyWithChatAction(['action' => Actions::TYPING]);
  }
}
