<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $directory = 'auth';

    public function index()
    {
        if (getUserLogin()) {
            return redirect(route('dashboard.index'));
        }
        $this->data['title'] = 'Login';
        return $this->render('index');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            setActivityLog('Login', ['email' => $credentials['email']]);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        setActivityLog('Logout');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}
