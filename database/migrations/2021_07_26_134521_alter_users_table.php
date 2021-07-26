<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration {
  
  public function up() {
    Schema::table('users', function (Blueprint $table) {
      $table->string('telegram_chat_id')->nullable();
    });
  }

  public function down() {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('telegram_chat_id');
    });
  }
}
