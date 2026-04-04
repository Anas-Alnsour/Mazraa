<x-owner-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-extrabold text-[#020617] tracking-tight">Account Settings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your profile, security preferences, and personal information.</p>
        </div>
    </x-slot>

    <div class="pb-24 max-w-4xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Section 1: Profile Information -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profile Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-xl">
                    @csrf
                    @method('patch')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required autocomplete="name">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" required autocomplete="username">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <p class="text-sm text-yellow-800 font-medium">
                                    Your email address is unverified.
                                    <button form="send-verification" class="underline text-yellow-900 hover:text-yellow-700 font-bold ml-1 focus:outline-none">
                                        Click here to re-send the verification email.
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-sm font-medium text-green-600">A new verification link has been sent to your email address.</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Phone (Optional, if you have it in DB) -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" placeholder="+962 7X XXX XXXX">
                        @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#020617] hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-[#1d5c42] flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Saved.
                            </p>
                        @endif
                    </div>
                </form>

                <!-- Hidden form for email verification resend -->
                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Payout Details (Bank Account)
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Enter your bank details to receive your farm booking revenues.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-xl">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-bold text-gray-700 mb-2">Bank Name</label>
                            <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name', $user->bank_name) }}" placeholder="e.g., Arab Bank" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium">
                            @error('bank_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="account_holder_name" class="block text-sm font-bold text-gray-700 mb-2">Account Holder Name</label>
                            <input id="account_holder_name" name="account_holder_name" type="text" value="{{ old('account_holder_name', $user->account_holder_name) }}" placeholder="e.g., Anas Alnsour" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium">
                            @error('account_holder_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="iban" class="block text-sm font-bold text-gray-700 mb-2">IBAN Number</label>
                        <input id="iban" name="iban" type="text" value="{{ old('iban', $user->iban) }}" placeholder="JO00 XXXX 0000 0000 0000 0000 0000 00" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-bold tracking-wider uppercase">
                        @error('iban') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#1d5c42] hover:bg-[#154531] text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                            Save Bank Details
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-[#1d5c42] flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Saved.
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Section 2: Update Password -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#020617] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1d5c42]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Update Password
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div>
                        <label for="update_password_current_password" class="block text-sm font-bold text-gray-700 mb-2">Current Password</label>
                        <input id="update_password_current_password" name="current_password" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" autocomplete="current-password">
                        @error('current_password', 'updatePassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="update_password_password" class="block text-sm font-bold text-gray-700 mb-2">New Password</label>
                        <input id="update_password_password" name="password" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" autocomplete="new-password">
                        @error('password', 'updatePassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#1d5c42]/20 focus:border-[#1d5c42] transition-colors text-[#020617] font-medium" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#020617] hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-[#1d5c42] flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Saved.
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Section 3: Delete Account (Danger Zone) -->
        <div class="bg-red-50 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-red-100 overflow-hidden" x-data="{ confirmingUserDeletion: false }">
            <div class="px-6 py-5 border-b border-red-200/50 bg-red-100/30 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-red-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Danger Zone
                    </h3>
                    <p class="mt-1 text-sm text-red-700">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <button @click="confirmingUserDeletion = true" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-red-600/20 hover:shadow-lg focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete Account
                </button>

                <!-- Modal Backdrop -->
                <div x-show="confirmingUserDeletion"
                     x-transition.opacity
                     class="fixed inset-0 z-50 bg-[#020617]/80 backdrop-blur-sm"
                     style="display: none;"></div>

                <!-- Modal Dialog -->
                <div x-show="confirmingUserDeletion"
                     x-transition.enter="transition ease-out duration-300"
                     x-transition.enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition.enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition.leave="transition ease-in duration-200"
                     x-transition.leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition.leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
                     style="display: none;"
                     @keydown.escape.window="confirmingUserDeletion = false">

                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-lg w-full transform transition-all p-6 sm:p-8" @click.away="confirmingUserDeletion = false">
                        <h2 class="text-xl font-extrabold text-[#020617]">Are you sure you want to delete your account?</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            Once your account is deleted, all of its resources, properties, and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                            @csrf
                            @method('delete')

                            <div>
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" name="password" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-colors text-[#020617] font-medium" placeholder="Confirm your Password" required @keydown.enter.prevent="$event.target.form.submit()">
                                @error('password', 'userDeletion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-3">
                                <button type="button" @click="confirmingUserDeletion = false" class="px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-all border border-gray-200 shadow-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-red-600/20">
                                    Permanently Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-owner-layout>
