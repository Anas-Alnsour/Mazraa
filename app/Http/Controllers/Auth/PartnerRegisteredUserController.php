<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // بيانات البنك (اختيارية عند التسجيل بس بنعملها فاليشن إذا تعبت)
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
        ]);

        // إنشاء الحساب وإعطاؤه صلاحية صاحب مزرعة
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'farm_owner', // 👈 أهم سطر عشان السيستم يتعرف عليه كصاحب مزرعة
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'iban' => $request->iban,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // توجيهه فوراً للداشبورد تبع الملاك
        return redirect()->route('owner.dashboard');
    }
}
