<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $login = trim($request->login);

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $user = User::where($field, $login)->first();

        /*
        |--------------------------------------------------------------------------
        | User Not Found
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'ឈ្មោះអ្នកប្រើប្រាស់ ឬ អ៊ីមែល មិនត្រឹមត្រូវ។',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Account Inactive
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'active') {

            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'គណនីនេះត្រូវបានបិទ។ សូមទាក់ទងអ្នកគ្រប់គ្រង។',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Password Incorrect
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($request->password, $user->password)) {

            return back()
                ->withInput()
                ->withErrors([
                    'password' => 'ពាក្យសម្ងាត់មិនត្រឹមត្រូវ។',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Login Success
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        $user->update([

            'last_login' => now(),
            'last_login_ip' => $request->ip(),

        ]);

        return redirect()->route('dashboard');
    }
}