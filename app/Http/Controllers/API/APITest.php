<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class APITest extends Controller
{
    //
    public function test(Request $request) {
        dd($request);
    }
}
