<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaterValuesTable extends Migration {

  public function up() {
    Schema::create('heater_values', function (Blueprint $table) {
      $table->id();

      $table->foreignId('heater_id')->constrained()->onDelete('cascade');

      $table->boolean("onoff")->default(false);
      $table->integer("phaseA")->default(0);
      $table->integer("phaseB")->default(0);
      $table->integer("phaseC")->default(0);
      $table->dateTime("last")->nullable();
      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('heater_values');
  }
}
