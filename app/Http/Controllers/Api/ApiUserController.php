<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiUserController extends ApiController
{
  public function getListUsers(Request $request)
  {
    if ($this->user->role == 1) {
      $users = User::all();
      foreach ($users as $user) {
        switch ($user->role) {
          case 1:
            $user->access = "Администратор";
            break;
          case 2:
            $user->access = "Руководитель";
            break;
          case 3:
            $user->access = "Исполнитель";
            break;
        }
      }
      return $users;
    } else {
      return "0";
    }
  }

  public function resetPass(Request $request)
  {
    $user = User::find($request->id);
    $user->password = Hash::make($request->newPass);
    $user->save();
    return "1";
  }

  public function deleteUser(Request $request)
  {
    $user = User::find($request->id);
    $user->delete();
    return "1";
  }

  public function addUser(Request $request)
  {
    $valid = User::where('phone', $request->newUser['phone']);
    if ($valid)
      return "2";
    $user = new User;
    $user->name = $request->newUser['name'];
    $user->phone = $request->newUser['phone'];
    $user->password = Hash::make($request->newUser['password']);
    $user->role = $request->newUser['role'];
    $user->api_token = Str::random(60);
    $user->save();
    return "1";
  }

  public function getListHandymans(){
    $handymans = User::where('role', 3)->get();
    return $handymans;
  }
}
