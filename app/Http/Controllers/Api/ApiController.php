<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
  public User $user;

  public function __construct(Request $request)
  {
    $user = User::where('api_token', $request->api_token)->first();
    if (!$user)
      dd("die");
    else
      $this->user = $user;
  }
}
