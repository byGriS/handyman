<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('works')->insert([
        'name' => "Объект 1",
        'status' => 1,
      ]);
      DB::table('works')->insert([
        'name' => "Объект 2",
        'status' => 0,
      ]);
      DB::table('works')->insert([
        'name' => "Объект 3",
        'status' => 1,
      ]);
    }
}
