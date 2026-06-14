<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PartnerRegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.partner-register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'regex:/^\+962(7[7-9][0-9]{7}|6[0-9]{7})$/','unique:users,phone'],

            // 👇 الحل المضمون 100% لمنع فحص الباسوورد على الإنترنت
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',             // لازم عالأقل 8 أحرف
                'regex:/[a-z]/',     // لازم عالأقل حرف صغير
                'regex:/[A-Z]/',     // لازم عالأقل حرف كبير
                'regex:/[0-9]/',     // لازم عالأقل رقم
                'regex:/[@$!%*#?&]/', // لازم عالأقل رمز من هدول
            ],

            'role' => ['required', 'string', 'in:farm_owner,supply_company,transport_company'],

            // Bank details (Optional)
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
        ], [
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ]);

        // Create the user with the selected role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'iban' => $request->iban,
            'governorate' => 'Amman', // نعطي قيمة افتراضية لتفادي أي مشاكل
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Dynamic Redirection based on selected role
        return match ($user->role) {
            'farm_owner'        => redirect()->route('owner.dashboard'),
            'supply_company'    => redirect()->route('supplies.dashboard'),
            'transport_company' => redirect()->route('transport.dashboard'),
            default             => redirect()->route('dashboard'),
        };
    }
}
