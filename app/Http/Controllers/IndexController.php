<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
  public function index()
  {
    if (Auth::check())
      return view('app')->with('user', Auth::user());
    else
      return view('auth.login');
  }

  public function test(){
    $tasks = Work::find(1)->tasks;
    dd($tasks[0]->user);
  }
}
