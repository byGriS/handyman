<?php

namespace App\Http\Controllers;

use App\Models\Heater;
use App\Models\HeaterValue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OwenCloudController extends Controller {
  public function getData() {
    $login = "vi.kamzul@icloud.com";
    $password = "viwdys-qudkez-buQvu4";

    $response = Http::post('https://api.owencloud.ru/v1/auth/open', [
      'login' => $login,
      'password' => $password
    ]);
    $token = json_decode($response->body(), true)['token'];

    $heater = Heater::first();
    $requestData = ["ids" => [$heater->onoff, $heater->phaseA, $heater->phaseB, $heater->phaseC]];

    $response = Http::withToken($token)->post("https://api.owencloud.ru/v1/parameters/last-data", $requestData);

    $data = json_decode($response->body(), true);

    $lastValue = HeaterValue::whereheaterId($heater->id)->orderBy('id', 'desc')->first();

    $newValue = new HeaterValue();
    $newValue->heater_id = $heater->id;
    $newValue->onoff = $data[0]['values'][0]["v"] == 1;
    $newValue->phaseA = intval($data[1]['values'][0]["v"]);
    $newValue->phaseB = intval($data[2]['values'][0]["v"]);
    $newValue->phaseC = intval($data[3]['values'][0]["v"]);
    $newValue->last = date("Y-m-d H:i:s", $data[3]['values'][0]["d"]);
    if ($newValue->last != $lastValue->last) {
      $newValue->save();
    }

    $sendNotice = 0;
    if ($newValue->onoff != $lastValue->onoff) {
      if ($newValue->onoff == 1)
        $sendNotice = 1;
      else
        $sendNotice = 2;
    }
    if (
      $newValue->onoff && $heater->state != "error" &&
      (
        abs($newValue->phaseA - $newValue->phaseB) > ($newValue->phaseA * $newValue->phaseB / 2 * 0.2) ||
        abs($newValue->phaseB - $newValue->phaseC) > ($newValue->phaseB * $newValue->phaseC / 2 * 0.2) ||
        abs($newValue->phaseC - $newValue->phaseA) > ($newValue->phaseC * $newValue->phaseA / 2 * 0.2) ||
        $newValue->phaseA < 10 ||
        $newValue->phaseB < 10 ||
        $newValue->phaseC < 10
      )
    ) {
      $sendNotice = 3;
      $heater->state = "error";
      $heater->save();
    } else {
      if ($heater->state == "error") {
        $heater->state = "ok";
        $heater->save();
      }
    }

    $diffMinutes = round(abs(strtotime("now") - strtotime($newValue->last)) / 60, 2);
    if ($diffMinutes > 10 && $heater->state != "timeout") {
      $sendNotice = 4;
      $heater->state = "timeout";
      $heater->save();
    }else{
      if ($heater->state == "timeout") {
        $heater->state = "ok";
        $heater->save();
      }
    }

    if ($sendNotice > 0) {
      switch ($sendNotice) {
        case 1:
          $text = $heater->title . " включен. Состояние: " . ($newValue->onoff ? "вкл." : "выкл.") . " фаза A " . $newValue->phaseA . "В, фаза B: " . $newValue->phaseB . "В, фаза C: " . $newValue->phaseC . "В, на " . (new \DateTime($newValue->last))->add(new \DateInterval("PT4H"))->format('Y-m-d H:i:s');
          break;
        case 2:
          $text = $heater->title . " выключен. Состояние: " . ($newValue->onoff ? "вкл." : "выкл.") . " фаза A: " . $newValue->phaseA . "В, фаза B: " . $newValue->phaseB . "В, фаза C: " . $newValue->phaseC . "В, на " . (new \DateTime($newValue->last))->add(new \DateInterval("PT4H"))->format('Y-m-d H:i:s');
          break;
        case 3:
          $text = $heater->title . " ошибка по напряжению. Состояние: " . ($newValue->onoff ? "вкл." : "выкл.") . " фаза A: " . $newValue->phaseA . "В, фаза B: " . $newValue->phaseB . "В, фаза C: " . $newValue->phaseC . "В, на " . (new \DateTime($newValue->last))->add(new \DateInterval("PT4H"))->format('Y-m-d H:i:s');
          break;
        case 4:
          $text = $heater->title . " не отвечает. Последнее состояние: " . ($newValue->onoff ? "вкл." : "выкл.") . " фаза A: " . $newValue->phaseA . "В, фаза B: " . $newValue->phaseB . "В, фаза C: " . $newValue->phaseC . "В, на " . (new \DateTime($newValue->last))->add(new \DateInterval("PT4H"))->format('Y-m-d H:i:s');
          break;
      }
      $telegram = new \Telegram\Bot\Api(config('telegram.bots.mybot.token'));
      $users = User::where('telegram_chat_id', '!=', null)->where('send_heater_notice', true)->get();
      foreach ($users as $user) {
        $telegram->setAsyncRequest(true)->sendMessage(['chat_id' => $user->telegram_chat_id, 'text' => $text]);
      }
    }



    return true;
  }
}
