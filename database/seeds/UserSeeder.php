<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          'name' => "Администратор",
          'phone' => "9876543210",
          'password' => Hash::make("123456"),
          'role' => 1,
          'api_token' => Str::random(60),
        ]);

        DB::table('users')->insert([
          'name' => "Иванов И.И.",
          'phone' => "9871234560",
          'password' => Hash::make("123456"),
          'role' => 3,
          'api_token' => Str::random(60),
        ]);
        DB::table('users')->insert([
          'name' => "Сидоров С.С.",
          'phone' => "9876541230",
          'password' => Hash::make("123456"),
          'role' => 2,
          'api_token' => Str::random(60),
        ]);
        DB::table('users')->insert([
          'name' => "Смирнов А.А.",
          'phone' => "9874563210",
          'password' => Hash::make("123456"),
          'role' => 3,
          'api_token' => Str::random(60),
        ]);
    }
}
