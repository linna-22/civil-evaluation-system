<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display Login Page
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Authenticate User
     */
    public function authenticate(LoginRequest $request)
    {
        $login = $request->login;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (
            Auth::attempt([
                $field => $login,
                'password' => $request->password,
                'status' => 'active',
            ])
        ) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()
            ->withInput()
            ->withErrors([
                'login' => 'Invalid username/email or password.',
            ]);
    }
}