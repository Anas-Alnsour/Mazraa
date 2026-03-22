<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalSessionController extends Controller
{
    public function create()
    {
        return view('auth.portal-login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // منع العملاء العاديين من الدخول من هنا
            if ($user->role === 'user') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Access Denied: Please use the Customer Login page.']);
            }

            $request->session()->regenerate();

            // التوجيه الذكي
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard', [], false) ?: redirect('/'),
                'farm_owner' => redirect()->route('owner.farms.index', [], false) ?: redirect('/'),
                'supply_company', 'transport_company' => redirect('/company-dashboard'),
                'supply_driver', 'transport_driver' => redirect('/driver-dashboard'),
                default => redirect('/'),
            };
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }
}
