<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{

  protected $table = 'works';

  public function tasks()
  {
    return $this->hasMany('App\Models\Task');
  }
}
