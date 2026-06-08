<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'loginform'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
    return view('dashboard');
    })->name('dashboard');

    Route::get('/form', function () {
        return view('pages.formulaire');
    })->name('form');

    Route::get('/trade', function () {
        return view('pages.journal');
    })->name('trade');

    Route::get('/tache', function () {
        return view('pages.link_automat');
    })->name('automatisation');
   
    Route::get('/ai', function () {
        return view('pages.ai');
    })->name('ai');

    Route::get('/message', function () {
        return view('pages.message');
    })->name('message');



    Route::get('/categorie',[CategoriesController::class, 'index'])->name('categories');
    Route::get('/chat',[ChatController::class, 'index'])->name('chat');

    
 });
