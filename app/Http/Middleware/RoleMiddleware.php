<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            // Redirect ke dashboard sesuai role masing-masing
            $dashRoute = match($userRole) {
                'admin'   => 'admin.dashboard',
                'manager' => 'manager.dashboard',
                'staff'   => 'staff.dashboard',
                default   => 'login',
            };

            return redirect()->route($dashRoute)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
