<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        if (!$result['success']) {
            return back()
                ->withErrors(['email' => $result['message']])
                ->withInput();
        }

        $user = $result['user'];

        ActivityLog::record('Login', 'User', $user->name, 'Role: ' . $user->role);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'manager') {
            return redirect()->route('manager.dashboard');
        }

        return redirect()->route('staff.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user) {
            ActivityLog::record('Logout', 'User', $user->name, 'Role: ' . $user->role);
        }

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
