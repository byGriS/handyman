<?php

namespace App\Http\Controllers\Api;

use App\Models\Heater;
use App\Models\HeaterValue;

class ApiHeaterController extends ApiController {
  public function getListHeaters() {
    $heaters = Heater::all();
    foreach ($heaters as &$heater) {
      $heater->lastParams = HeaterValue::whereheaterId($heater->id)->orderBy('id', 'desc')->take(15)->get();
    }
    return $heaters;
  }
}
