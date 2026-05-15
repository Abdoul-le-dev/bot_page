<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserMediaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\CategoriesController;

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/form', function () {
    return view('pages.formulaire');
});

Route::get('/trade', function () {
    return view('pages.journal');
});

Route::get('/tache', function () {
    return view('pages.link_automat');
});
Route::get('/test', function () {
    return view('pages.text');
});
Route::get('/ai', function () {
    return view('pages.ai');
});



Route::get('/categorie',[CategoriesController::class, 'index'])->name('categories');
Route::get('/chat',[ChatController::class, 'index'])->name('chat');



// Route::middleware(['auth'])->group(function () {
 
//     //Route::get('/dashboard',                  [DashboardController::class,   'index'])->name('dashboard');
//     Route::get('/stats',             [StatsController::class,       'index'])->name('stats');
 
//     // Membres
//     Route::get('/users',             [UserController::class,        'index'])->name('users');
//     Route::post('/users/{user}/tag', [UserController::class,        'tag'])->name('users.tag');
 
//     // Formulaires
//     Route::get('/forms',             [FormController::class,        'index'])->name('forms');
//     Route::get('/forms/create',      [FormController::class,        'create'])->name('forms.create');
//     Route::post('/forms',            [FormController::class,        'store'])->name('forms.store');
//     Route::get('/forms/{form}',      [FormController::class,        'show'])->name('forms.show');
//     Route::put('/forms/{form}',      [FormController::class,        'update'])->name('forms.update');
 
//     // Messages
//     Route::get('/messages',          [MessageController::class,     'index'])->name('messages');
//     Route::get('/messages/create',   [MessageController::class,     'create'])->name('messages.create');
//     Route::post('/messages',         [MessageController::class,     'store'])->name('messages.store');
 
//     // Chat
//     Route::get('/chat',              [ChatController::class,        'index'])->name('chat');
//     Route::get('/chat/{user}',       [ChatController::class,        'show'])->name('chat.show');
//     Route::post('/chat/{user}',      [ChatController::class,        'send'])->name('chat.send');
 
//     // Agent IA
//     Route::get('/ai',                [AiController::class,          'index'])->name('ai');
//     Route::put('/ai/config',         [AiController::class,          'updateConfig'])->name('ai.config');
//     Route::post('/ai/{conv}/take',   [AiController::class,          'takeOver'])->name('ai.takeover');
 
//     // Journal
//     Route::get('/journal',           [JournalController::class,     'index'])->name('journal');
 
//     // Témoignages
//     Route::get('/testimonials',      [TestimonialController::class, 'index'])->name('testimonials');
//     Route::post('/testimonials/{t}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
 
//     // Abonnements
//     Route::get('/subscriptions',     [SubscriptionController::class,'index'])->name('subscriptions');
//     Route::post('/subscriptions/{sub}/remind', [SubscriptionController::class,'remind'])->name('subscriptions.remind');
 
//     // Rendez-vous
//     Route::get('/rdv',               [AppointmentController::class, 'index'])->name('rdv');
 
//     // Liens
//     Route::get('/links',             [TrackingLinkController::class,'index'])->name('links');
//     Route::post('/links',            [TrackingLinkController::class,'store'])->name('links.store');
 
//     // Notifications
//     Route::post('/notifications/clear', [NotificationController::class,'clear'])->name('notifications.clear');
 
    
// });
