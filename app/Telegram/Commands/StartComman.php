<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command {

  protected $name = "start";

  protected $description = "Команда инициализации чата";

  public function handle() {
    $this->replyWithMessage(['text' => 'Отправьте команду со своим номером телефон без первой 8 (в формате: /user 9876543210)']);
    $this->replyWithChatAction(['action' => Actions::TYPING]);
  }
}
