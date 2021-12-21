<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

  public function user() {
    return $this->belongsTo('App\Models\User')->withTrashed();
  }

  public function work() {
    return $this->belongsTo('App\Models\Work');
  }

  public function notes() {
    return $this->hasMany('App\Models\Note');
  }
}
