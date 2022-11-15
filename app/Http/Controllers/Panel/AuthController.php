<?php

namespace App\Http\Controllers\Panel;

use App\Support\Str;
use Illuminate\Http\Request;

class AuthController extends PanelController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($locale = null)
    {
        $data = (object)[];
        $data->title = __('messages.fields.login');
        return view('panel.auth.login', (array)$data);
    }

    public function logout($locale = null)
    {
        auth()->logout();
        return redirect(route('panel.login', app()->getLocale()));
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => ['required', 'max:255'],
            'password' => ['required', 'max:255'],
        ]);

        $temp = $request->only('username', 'password');
        $credentials = $temp;
        if (Str::of($temp['username'])->contains('@')) {
            $credentials = ['email' => $temp['username'], 'password' => $temp['password']];
        }

        if (auth()->attempt($credentials, $request->remember == 'on')) {
            // Authentication passed...
            return redirect(route('panel.index', app()->getLocale()));
        }

        return redirect(route('panel.login', app()->getLocale()))->withErrors(['Invalid credentials']);
    }
}
