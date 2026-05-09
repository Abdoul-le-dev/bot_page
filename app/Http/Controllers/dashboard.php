<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Trade;
use App\Models\Notification;
use App\Models\AiConversation;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
       
        return view('dashboard');
    }
}