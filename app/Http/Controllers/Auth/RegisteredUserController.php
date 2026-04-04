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

class RegisteredUserController extends Controller
{
    /**
     * Display the consumer registration view.
     */
    public function create(): View
    {
        return view('auth.register', ['role' => 'user']);
    }

    /**
     * Handle an incoming consumer registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->registerUser($request, 'user');
    }

    /**
     * Core registration logic (internal use for normal users).
     */
    private function registerUser(Request $request, string $assignedRole): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required','regex:/^(?:((078|077|079)[0-9]{7})|(06[0-9]{7}))$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $assignedRole, // Strictly assigned internally based on the route
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }

    /**
     * Display the registration view for partners (farm owners).
     */
    public function createPartner(): \Illuminate\View\View
    {
        // 💡 التعديل هون: بيفتح صفحة البارتنر الجديدة الفخمة اللي عملناها
        return view('auth.partner-register');
    }

    /**
     * Handle an incoming partner registration request.
     */
    public function storePartner(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // الـ Validation هون رجعته يقبل أي رقم عشان ما يعقد صاحب المزرعة، بتقدر تغيره لـ regex إذا بدك
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // بيانات البنك
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'farm_owner', // تعيين دور المالك
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'iban' => $request->iban,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // توجيه مباشر للداشبورد تبع الملاك
        return redirect()->route('owner.dashboard');
    }
}
