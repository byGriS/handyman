<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaterValue extends Model
{
  public function heater() {
    return $this->belongsTo('App\Models\Heater');
  }
}
