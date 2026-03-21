<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // فحص إذا المستخدم مسجل دخول وصلاحيته أدمن
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // إذا مش أدمن، اعطيه صفحة 403 ممنوع الدخول
        abort(403, 'Unauthorized Access.');
    }
}
