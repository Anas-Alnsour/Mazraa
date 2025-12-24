{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Register')

@section('content')
    <div class="relative min-h-[calc(100vh-8rem)]"
        style="background-image:url('{{ asset('backgrounds/login&register.jpg') }}');
            background-size:cover;background-position:center;">
        {{-- طبقة تعتيم خفيفة فوق الخلفية --}}
        <div class="absolute inset-0 bg-black/45"></div>

        {{-- الكارد الزجاجي --}}
        <div class="relative z-10 flex justify-center items-center min-h-[calc(100vh-8rem)] px-4">
            <div
                class="w-full max-w-xl bg-white/20 backdrop-blur-xl rounded-3xl shadow-2xl
                    border border-white/40 p-10">
                <h2 class="text-4xl font-extrabold text-white/90 mb-2 text-center">Register</h2>
                <p class="text-white/80 mb-8 text-center">Create your account to manage transport requests efficiently</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="flex flex-row gap-6">
                        <div class="flex-1">
                            <label for="name" class="text-white/90 font-semibold mb-2 block">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                    autofocus
                                    class="w-full px-5 py-3 rounded-2xl shadow-sm border border-white/50
                                  bg-white/70 focus:bg-white focus:border-green-500
                                  focus:ring-2 focus:ring-green-300 transition" />                            
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-200" />
                        </div>

                        <div class="flex-1">
                            <label for="phone" class="text-white/90 font-semibold mb-2 block">phone number</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                                      placeholder="Ex: 078*******"
                                      class="w-full px-7 py-3 rounded-2xl shadow-sm border border-white/50
                                  bg-white/70 focus:bg-white focus:border-green-500
                                  focus:ring-2 focus:ring-green-300 transition" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-1 text-sm text-red-200" />
                        </div>
                    </div>

                    <div>
                        <label for="email" class="text-white/90 font-semibold mb-2 block">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full px-5 py-3 rounded-2xl shadow-sm border border-white/50
                                  bg-white/70 focus:bg-white focus:border-green-500
                                  focus:ring-2 focus:ring-green-300 transition" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-200" />
                    </div>

                    <div>
                        <label for="password" class="text-white/90 font-semibold mb-2 block">Password</label>
                        <input id="password" name="password" type="password" required
                            class="w-full px-5 py-3 rounded-2xl shadow-sm border border-white/50
                                  bg-white/70 focus:bg-white focus:border-green-500
                                  focus:ring-2 focus:ring-green-300 transition" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-200" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-white/90 font-semibold mb-2 block">Confirm
                            Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full px-5 py-3 rounded-2xl shadow-sm border border-white/50
                                  bg-white/70 focus:bg-white focus:border-green-500
                                  focus:ring-2 focus:ring-green-300 transition" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-200" />
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-between mt-6 gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-white/90 underline hover:text-white">Already
                            registered?</a>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl
                                   shadow-lg font-semibold transition w-full md:w-auto">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
