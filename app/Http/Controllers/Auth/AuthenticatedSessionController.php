<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        session(['role' => auth()->user()->role]);

        $role = auth()->user()->role;

        $redirectUrl = match ($role) {
            'admin' => '/admin/dashboard',
            'farm_owner' => '/owner/dashboard',
            'supply_company' => '/supplies/dashboard',
            'transport_company' => '/transport/dashboard',
            'supply_driver' => '/delivery/orders',
            'transport_driver' => '/shuttle/trips',
            default => '/',
        };

        return redirect()->intended($redirectUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
