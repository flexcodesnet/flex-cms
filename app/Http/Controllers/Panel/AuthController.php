<?php

namespace App\Http\Controllers\Panel;

use FXC\Base\Http\Controllers\PanelController;
use FXC\Base\Supports\Str;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AuthController extends PanelController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $locale
     * @return Factory|View|Application
     */
    public function login($locale = null)
    {
        $data = (object) [];
        $data->title = __('messages.fields.login');

        return view('panel.auth.login', (array) $data);
    }

    /**
     * @param $locale
     * @return Redirector|Application|RedirectResponse
     */
    public function logout($locale = null)
    {
        auth()->logout();
        return redirect(route('panel.login', app()->getLocale()));
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  Request  $request
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
