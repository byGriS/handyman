<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('tasks')->insert([
      'typeWork' => 1,
      'capacity' => 12,
      'end' => Carbon::create(2020, 5, 20)->toDateTimeString(),
      'standartPeople' => 1.8,
      'standartConsumption' => 1.2,
      'status' => 1,
      'work_id' => 1,
      'user_id' => 2,
      'created_at' => Carbon::create(2020, 4, 25)->toDateTimeString(),
    ]);
    DB::table('tasks')->insert([
      'typeWork' => 1,
      'capacity' => 25,
      'end' => Carbon::create(2020, 5, 25)->toDateTimeString(),
      'standartPeople' => 3,
      'standartConsumption' => 2,
      'status' => 1,
      'work_id' => 3,
      'user_id' => 4,
      'created_at' => Carbon::create(2020, 4, 25)->toDateTimeString(),
    ]);
  }
}
