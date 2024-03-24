<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeatersTable extends Migration {

  public function up() {
    Schema::create('heaters', function (Blueprint $table) {
      $table->id();
      $table->string("title");      
      $table->string("onoff")->nullable();
      $table->string("phaseA")->nullable();
      $table->string("phaseB")->nullable();
      $table->string("phaseC")->nullable();
      $table->string("state")->nullable();
      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('heaters');
  }
}
