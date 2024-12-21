<?php

use App\Http\Controllers\API\APITest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', [APITest::class, 'test'])->middleware('auth:sanctum');
