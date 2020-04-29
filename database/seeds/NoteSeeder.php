<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('notes')->insert(
      [
        'people' => 2,
        'consumption' => 4,
        'dt' => Carbon::create(2020, 4, 26)->toDateTimeString(),
        'task_id' => 1
      ]
    );
    DB::table('notes')->insert(
      [
        'people' => 3,
        'consumption' => 6,
        'dt' => Carbon::create(2020, 4, 25)->toDateTimeString(),
        'task_id' => 1
      ]
    );
  }
}
