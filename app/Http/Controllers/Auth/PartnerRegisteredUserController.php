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
            'role' => ['required', 'string', 'in:farm_owner,supply_company,transport_company'],

            // Bank details (Optional)
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
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
