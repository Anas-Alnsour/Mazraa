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
     * Display the registration view.
     */
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
     * Display the partner (Farm Owner) registration view.
     */
    public function createPartner(): View
    {
        return view('auth.register', ['role' => 'farm_owner']);
    }

    /**
     * Handle an incoming partner registration request.
     */
    public function storePartner(Request $request): RedirectResponse
    {
        return $this->registerUser($request, 'farm_owner');
    }

    /**
     * Core registration logic (internal use).
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

        // Redirect based on the role they just registered for
        if ($assignedRole === 'farm_owner') {
            return redirect('/owner/dashboard');
        }

        return redirect('/');
    }
}
