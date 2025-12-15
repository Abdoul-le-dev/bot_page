<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserMediaController;

Route::get('/', function () {
    return view('index');
});

Route::post('/media',[UserMediaController::class, 'upload' ]);
