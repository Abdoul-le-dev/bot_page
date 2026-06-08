<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function loginForm()
    {   
    //     DB::table('users')->insert([
    //     'name' => 'fiacre',
    //     'email' => '',
    //     'password' => Hash::make(''),
    //     'created_at' => now(),
    //     'updated_at' => now(),
    // ]);
        if (Auth::check() ) {
            return $this->redirectAfterLogin(Auth::user());
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            // if (! in_array($user->role, ['super_admin', 'admin', 'user'])) {
            //     Auth::logout();
            //     return back()->withErrors(['email' => 'Accès non autorisé.']);
            // }
            $request->session()->regenerate();
            return $this->redirectAfterLogin($user);
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->withInput();
    }

    private function redirectAfterLogin($user)
    {
        return view('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->view('login');
    }
}
