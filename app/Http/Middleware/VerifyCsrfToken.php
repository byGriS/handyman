<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware {
  protected $except = [
    '/1896948525:AAHcOXKulESgNKRNZq1_CJvQ3HAsYremVEU/webhook'
  ];
}
