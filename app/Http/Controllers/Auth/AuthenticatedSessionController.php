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

    /**
     * معالجة تسجيل الدخول والتوجيه
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = Auth::user();

        // --- STRICT B2B / B2C GATEWAY LOGIC ---
        $isPortalLogin = $request->routeIs('portal.login') || $request->has('portal_login');

        if ($isPortalLogin && $user->role === 'user') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => 'Access Denied: Please use the Customer Login gateway.',
            ]);
        }

        if (!$isPortalLogin && $user->role !== 'user') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => 'Access Denied: Please use the Partner Portal gateway.',
            ]);
        }
        // --------------------------------------

        $request->session()->regenerate();
        session(['role' => $user->role]);

        // توجيه صارم وإجباري (تم تصحيح المسارات لتطابق web.php 100%)
$redirectUrl = match ($user->role) {
            'admin'             => '/admin',
            'farm_owner'        => '/owner/dashboard', 
            'supply_company'    => '/supplies/dashboard',
            'transport_company' => '/transport/dashboard',
            'supply_driver'     => '/delivery/orders',
            'transport_driver'  => '/shuttle/trips',
            'user'              => '/dashboard',
            default             => '/',
        };

        // إرجاع توجيه إجباري (تم مسح intended)
        return redirect($redirectUrl);
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
