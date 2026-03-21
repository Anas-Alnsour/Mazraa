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
     * Display the consumer login view.
     */
    public function create(): View
    {
        return view('auth.login', ['portal' => false]);
    }

    /**
     * Display the business/partner portal login view.
     */
    public function createPortal(): View
    {
        return view('auth.login', ['portal' => true]);
    }


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Save role in session for UI/Layout checks if needed
        session(['role' => $user->role]);

        $redirectUrl = match ($user->role) {
            'admin'             => '/admin',
            'farm_owner'        => '/owner/dashboard',
            'supply_company'    => '/supplies/dashboard',
            'transport_company' => '/transport/dashboard',
            'supply_driver'     => '/delivery/orders',
            'transport_driver'  => '/shuttle/trips',
            'user'              => '/dashboard',
            default             => '/', // Fallback just in case
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
