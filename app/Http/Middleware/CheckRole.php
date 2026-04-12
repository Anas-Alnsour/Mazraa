<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // ✅ تم حل مشكلة الـ Loop، بدلاً من رمي خطأ أو توجيهه للرئيسية يتم تحويله للوحته الصحيحة
        if (!in_array($user->role, $roles)) {
            $errorMessage = 'Unauthorized Access. You have been redirected to your dashboard.';

            return match ($user->role) {
                'admin'             => redirect()->route('admin.dashboard')->with('error', $errorMessage),
                'farm_owner'        => redirect()->route('owner.dashboard')->with('error', $errorMessage),
                'transport_company' => redirect()->route('transport.dashboard')->with('error', $errorMessage),
                'supply_company'    => redirect()->route('supplies.dashboard')->with('error', $errorMessage),
                'transport_driver'  => redirect()->route('driver.transport.dashboard')->with('error', $errorMessage),
                'supply_driver'     => redirect()->route('driver.supply.dashboard')->with('error', $errorMessage),
                default             => redirect()->route('home')->with('error', $errorMessage)
            };
        }

        return $next($request);
    }
}
