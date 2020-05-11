<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('typeWork');            
            $table->float('capacity');
            $table->date('end');
            $table->float('standartPeople');//чел/сут/м3
            $table->float('standartConsumption');//м3/м3 или м3/м2 раствор/кладка
            $table->integer('holiday');
            $table->boolean('status')->default(1);    

            $table->foreignId('work_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
