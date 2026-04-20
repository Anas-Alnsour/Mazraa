<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * شاشة دخول العملاء العاديين
     */
    public function create(Request $request): View
    {
        return view('auth.login', ['isPortal' => false]);
    }

    /**
     * شاشة دخول الشركات والملاك (الواجهة الرسمية)
     */
    public function createPortal(): View
    {
        // تم تغييرها لترجع صفحة مستقلة تماماً للشركاء
        return view('auth.portal-login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = Auth::user();

        $isPortalLogin = $request->input('portal_login') == '1';
        $expectedRole  = $request->input('expected_role');

        if (!$isPortalLogin && $user->role !== 'user') {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'This login is only for customers.',
            ]);
        }

        if ($isPortalLogin && $user->role === 'user') {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Please use the normal login page.',
            ]);
        }

        if ($expectedRole && $user->role !== $expectedRole) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Wrong login page for your role.',
            ]);
        }

        $request->session()->regenerate();

        return redirect(match ($user->role) {
            'admin'             => '/admin',
            'farm_owner'        => '/owner/dashboard',
            'supply_company'    => '/supplies/dashboard',
            'transport_company' => '/transport/dashboard',
            'transport_driver'  => '/driver/transport/dashboard',
            'supply_driver'     => '/driver/supply/dashboard',
            'user'              => '/dashboard',
            default             => '/',
        });
    }

    /**
     * تسجيل الخروج
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
