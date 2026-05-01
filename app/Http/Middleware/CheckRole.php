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

        // فحص الصلاحية وتوجيه المستخدم بناءً على دوره إذا حاول الدخول لمكان غير مسموح له
        if (!in_array($user->role, $roles)) {
            $errorMessage = 'Unauthorized Access. You have been redirected to your dashboard.';

            return match ($user->role) {
                'admin'             => redirect()->route('admin.dashboard')->with('error', $errorMessage),
                'farm_owner'        => redirect()->route('owner.dashboard')->with('error', $errorMessage),
                'transport_company' => redirect()->route('transport.dashboard')->with('error', $errorMessage),
                'supply_company'    => redirect()->route('supplies.dashboard')->with('error', $errorMessage),
                
                // ✅ التصحيح: مطابقة الأسماء مع ملف web.php
                'transport_driver'  => redirect()->route('transport.driver.dashboard')->with('error', $errorMessage),
                'supply_driver'     => redirect()->route('supply.driver.dashboard')->with('error', $errorMessage),
                
                default             => redirect()->route('home')->with('error', $errorMessage)
            };
        }

        return $next($request);
    }
}