<x-owner-layout>
    <x-slot name="header">Security Hub</x-slot>

    <style>
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
        /* Custom Input Autofill Dark Mode Fix */
        input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #020617 inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>

    <div class="max-w-[96%] xl:max-w-5xl mx-auto space-y-12 pb-24 animate-god-in">

        {{-- 🌟 1. Header Panel --}}
        <div class="relative bg-slate-900/80 rounded-[3rem] p-10 md:p-14 border border-slate-800 backdrop-blur-2xl shadow-2xl overflow-hidden fade-in-up">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-[#c2a265]/10 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-8">
                <div class="w-20 h-20 rounded-[2rem] bg-gradient-to-tr from-[#c2a265]/20 to-yellow-900/20 border border-[#c2a265]/30 flex items-center justify-center text-[#c2a265] shadow-[0_0_30px_rgba(194,162,101,0.2)] shrink-0">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">Security <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c2a265] to-yellow-500">Hub</span></h1>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Manage your profile, financial routing, and security protocols.</p>
                </div>
            </div>
        </div>

        {{-- 🌟 2. Profile Information (Emerald Palette) --}}
        <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-xl relative fade-in-up" style="animation-delay: 0.1s;">
            <div class="absolute -left-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none"></div>

            <div class="px-8 md:px-12 py-8 border-b border-slate-800 bg-slate-950/40 flex items-center justify-between relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-white flex items-center gap-3 tracking-tight">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Identity Metadata
                    </h3>
                    <p class="mt-1.5 text-[10px] font-black uppercase tracking-widest text-slate-500">Update network profile & communications.</p>
                </div>
            </div>

            <div class="p-8 md:p-12 relative z-10">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-8 max-w-2xl">
                    @csrf
                    @method('patch')

                    <div class="space-y-3">
                        <label for="name" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Full Name / Entity</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-bold shadow-inner outline-none" required autocomplete="name">
                        @error('name') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Email Protocol</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-bold font-mono shadow-inner outline-none" required autocomplete="username">
                        @error('email') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-4 p-5 bg-amber-500/10 rounded-2xl border border-amber-500/30 flex flex-col gap-2">
                                <p class="text-[11px] text-amber-400 font-bold tracking-wide">
                                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Your email address is unverified.
                                </p>
                                <button form="send-verification" class="text-left text-[10px] font-black uppercase tracking-widest text-amber-500 hover:text-amber-300 transition-colors underline underline-offset-4 decoration-amber-500/30 hover:decoration-amber-300">
                                    Click here to re-send verification email.
                                </button>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-emerald-400">A new verification link has been dispatched.</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <label for="phone" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Secure Phone</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone ?? '') }}" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-bold font-mono tracking-widest shadow-inner outline-none" placeholder="+962 7X XXX XXXX">
                        @error('phone') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-6 pt-4">
                        <button type="submit" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-slate-950 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(16,185,129,0.2)] active:scale-95 transition-all flex items-center justify-center gap-2">
                            Commit Metadata
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[10px] font-black uppercase tracking-widest text-emerald-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Protocol Saved
                            </p>
                        @endif
                    </div>
                </form>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">@csrf</form>
            </div>
        </div>

        {{-- 🌟 3. Financial Bridge (Gold Palette) --}}
        <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-xl relative fade-in-up" style="animation-delay: 0.2s;">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-[#c2a265]/5 rounded-full blur-[80px] pointer-events-none"></div>

            <div class="px-8 md:px-12 py-8 border-b border-slate-800 bg-slate-950/40 flex items-center justify-between relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-white flex items-center gap-3 tracking-tight">
                        <svg class="w-6 h-6 text-[#c2a265]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Financial Bridge
                    </h3>
                    <p class="mt-1.5 text-[10px] font-black uppercase tracking-widest text-slate-500">Bank routing parameters for payouts.</p>
                </div>
            </div>

            <div class="p-8 md:p-12 relative z-10">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-8 max-w-2xl">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="bank_name" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Institution Name</label>
                            <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name', $user->bank_name) }}" placeholder="e.g. Arab Bank" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-[#c2a265] focus:ring-1 focus:ring-[#c2a265] transition-all font-bold shadow-inner outline-none">
                            @error('bank_name') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label for="account_holder_name" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Account Holder</label>
                            <input id="account_holder_name" name="account_holder_name" type="text" value="{{ old('account_holder_name', $user->account_holder_name) }}" placeholder="e.g. Anas Alnsour" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-[#c2a265] focus:ring-1 focus:ring-[#c2a265] transition-all font-bold shadow-inner outline-none">
                            @error('account_holder_name') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="iban" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">IBAN Code</label>
                        <input id="iban" name="iban" type="text" value="{{ old('iban', $user->iban) }}" placeholder="JO00 XXXX 0000 ..." class="w-full bg-slate-950 border border-slate-700 text-[#c2a265] rounded-2xl px-5 py-4 focus:border-[#c2a265] focus:ring-1 focus:ring-[#c2a265] transition-all font-black font-mono tracking-[0.15em] uppercase shadow-inner outline-none">
                        @error('iban') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-6 pt-4">
                        <button type="submit" class="px-8 py-4 bg-gradient-to-r from-[#c2a265] to-yellow-600 hover:to-yellow-500 text-[#020617] text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(194,162,101,0.2)] active:scale-95 transition-all flex items-center justify-center gap-2">
                            Update Routing
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[10px] font-black uppercase tracking-widest text-[#c2a265] flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Configuration Secured
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- 🌟 4. Update Password (Indigo Palette) --}}
        <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-xl relative fade-in-up" style="animation-delay: 0.3s;">
            <div class="absolute -left-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-[80px] pointer-events-none"></div>

            <div class="px-8 md:px-12 py-8 border-b border-slate-800 bg-slate-950/40 flex items-center justify-between relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-white flex items-center gap-3 tracking-tight">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Security Keys
                    </h3>
                    <p class="mt-1.5 text-[10px] font-black uppercase tracking-widest text-slate-500">Rotate authorization credentials.</p>
                </div>
            </div>

            <div class="p-8 md:p-12 relative z-10">
                <form method="post" action="{{ route('password.update') }}" class="space-y-8 max-w-2xl">
                    @csrf
                    @method('put')

                    <div class="space-y-3">
                        <label for="update_password_current_password" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Current Security Key</label>
                        <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-bold shadow-inner outline-none tracking-widest" autocomplete="current-password">
                        @error('current_password', 'updatePassword') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="update_password_password" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">New Security Key</label>
                        <input id="update_password_password" name="password" type="password" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-bold shadow-inner outline-none tracking-widest" autocomplete="new-password">
                        @error('password', 'updatePassword') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="update_password_password_confirmation" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Verify New Key</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-slate-950 border border-slate-700 text-white rounded-2xl px-5 py-4 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-bold shadow-inner outline-none tracking-widest" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword') <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-rose-500 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-6 pt-4">
                        <button type="submit" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(99,102,241,0.2)] active:scale-95 transition-all flex items-center justify-center gap-2">
                            Rotate Protocol
                        </button>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[10px] font-black uppercase tracking-widest text-indigo-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Key Secured
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- 🌟 5. Delete Account (Danger Zone - Rose Palette) --}}
        <div class="bg-rose-500/5 rounded-[3rem] border border-rose-500/20 shadow-2xl overflow-hidden backdrop-blur-xl relative fade-in-up" style="animation-delay: 0.4s;" x-data="{ confirmingUserDeletion: false }">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')] opacity-5 pointer-events-none"></div>

            <div class="px-8 md:px-12 py-8 border-b border-rose-500/10 bg-rose-950/20 flex items-center justify-between relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-rose-500 flex items-center gap-3 tracking-tight">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Danger Zone
                    </h3>
                    <p class="mt-1.5 text-[10px] font-black uppercase tracking-widest text-rose-400/60">Irreversible node termination.</p>
                </div>
            </div>

            <div class="p-8 md:p-12 relative z-10">
                <button @click="confirmingUserDeletion = true" class="px-8 py-4 bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-[0_10px_20px_rgba(244,63,94,0.2)] active:scale-95 transition-all flex items-center justify-center gap-2">
                    Initiate Purge Protocol
                </button>

                {{-- 🔴 DANGER MODAL (GOD MODE) --}}
                <div x-show="confirmingUserDeletion" x-transition.opacity class="fixed inset-0 z-[200] bg-[#020617]/95 backdrop-blur-md" style="display: none;"></div>

                <div x-show="confirmingUserDeletion"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="fixed inset-0 z-[250] flex items-center justify-center p-4 sm:p-6" style="display: none;" @keydown.escape.window="confirmingUserDeletion = false">

                    <div class="bg-slate-900 rounded-[3rem] shadow-[0_0_80px_rgba(244,63,94,0.3)] overflow-hidden max-w-lg w-full p-10 border border-rose-500/30" @click.away="confirmingUserDeletion = false">
                        <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-rose-500/20 shadow-inner">
                            <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-center text-white tracking-tighter mb-4">Warning: Final Action</h2>
                        <p class="text-[11px] font-bold text-slate-400 text-center leading-relaxed uppercase tracking-widest mb-8">
                            Once your node is terminated, all associated assets and ledger data will be purged. Authenticate below to proceed.
                        </p>

                        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                            @csrf @method('delete')
                            <div>
                                <input id="password" name="password" type="password" class="w-full bg-slate-950 border border-rose-500/30 text-white rounded-2xl px-5 py-4 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all font-bold shadow-inner outline-none text-center tracking-widest placeholder:text-slate-600" placeholder="ENTER SECURITY KEY" required @keydown.enter.prevent="$event.target.form.submit()">
                                @error('password', 'userDeletion') <p class="mt-2 text-[10px] font-black text-center uppercase tracking-widest text-rose-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="button" @click="confirmingUserDeletion = false" class="flex-1 py-4 bg-transparent text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all rounded-xl border border-slate-700">Abort</button>
                                <button type="submit" class="flex-1 py-4 bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-[0_10px_20px_rgba(244,63,94,0.3)] transition-all active:scale-95">Purge</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-owner-layout>
