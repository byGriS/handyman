<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable2 extends Migration {

  public function up() {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('send_heater_notice')->default(false);
    });
  }

  public function down() {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('send_heater_notice');
    });
  }
}
