<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        return redirect()->back();
    }
}
