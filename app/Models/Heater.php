<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Heater extends Model
{
  public function values() {
    return $this->hasMany('App\Models\HeaterValue');
  }

  public function params() {
    return $this->hasMany('App\Models\HeaterParam');
  }
}
