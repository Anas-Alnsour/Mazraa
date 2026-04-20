@props([
    'id' => 'password',
    'name' => 'password',
    'placeholder' => '••••••••',
    'required' => true,
    'autocomplete' => 'new-password',
    'color' => 'green',
    'type' => 'signup',
])

@php
    $themes = [
        'green' => [
            'focus' => 'text-[#1d5c42]',
            'ring' => 'focus:ring-[#1d5c42]/20',
            'border' => 'focus:border-[#1d5c42]',
            'accent' => 'text-[#1d5c42]',
            'hover' => 'hover:text-[#154230]',
        ],
        'blue' => [
            'focus' => 'text-blue-600',
            'ring' => 'focus:ring-blue-600/20',
            'border' => 'focus:border-blue-600',
            'accent' => 'text-blue-600',
            'hover' => 'hover:text-blue-700',
        ],
        'indigo' => [
            'focus' => 'text-indigo-600',
            'ring' => 'focus:ring-indigo-600/20',
            'border' => 'focus:border-indigo-600',
            'accent' => 'text-indigo-600',
            'hover' => 'hover:text-indigo-700',
        ],
        'teal' => [
            'focus' => 'text-teal-600',
            'ring' => 'focus:ring-teal-600/20',
            'border' => 'focus:border-teal-600',
            'accent' => 'text-teal-600',
            'hover' => 'hover:text-teal-700',
        ],
        'rose' => [
            'focus' => 'text-rose-600',
            'ring' => 'focus:ring-rose-600/20',
            'border' => 'focus:border-rose-600',
            'accent' => 'text-rose-600',
            'hover' => 'hover:text-rose-700',
        ],
        'amber' => [
            'focus' => 'text-amber-600',
            'ring' => 'focus:ring-amber-600/20',
            'border' => 'focus:border-amber-600',
            'accent' => 'text-amber-600',
            'hover' => 'hover:text-amber-700',
        ],
        'gray' => [
            'focus' => 'text-gray-600',
            'ring' => 'focus:ring-gray-600/20',
            'border' => 'focus:border-gray-600',
            'accent' => 'text-gray-600',
            'hover' => 'hover:text-gray-700',
        ],
    ];
    $theme = $themes[$color] ?? $themes['green'];
@endphp

<div class="group" x-data="{ show: false }">
    <div class="flex items-center justify-between mb-2">
        <label for="{{ $id }}"
            class="block text-[11px] font-black uppercase tracking-widest text-gray-500 transition-colors group-focus-within:{{ $theme['focus'] }}">
            {{ $slot }}
        </label>
        @if ($type === 'login' && Route::has('password.request'))
            <a href="{{ route('password.request') }}"
                class="text-[11px] font-black uppercase tracking-widest {{ $theme['accent'] }} {{ $theme['hover'] }} transition-colors">
                Forgot password?
            </a>
        @endif
    </div>

    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
            <svg class="h-6 w-6 text-gray-400 group-focus-within:{{ $theme['focus'] }} transition-colors" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <input id="{{ $id }}" name="{{ $name }}" :type="show ? 'text' : 'password'"
            autocomplete="{{ $autocomplete }}" {{ $required ? 'required' : '' }}
            class="pl-14 pr-14 block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl shadow-sm focus:bg-white {{ $theme['ring'] }} {{ $theme['border'] }} sm:text-base py-4 leading-tight font-medium transition-all duration-300"
            placeholder="{{ $placeholder }}">

        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
            <button type="button" @click="show = !show"
                class="p-1 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition" tabindex="-1">

                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.584 10.587a2 2 0 002.829 2.829" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.88 5.09A9.953 9.953 0 0112 5c4.478 0 8.27 2.944 9.543 7a9.97 9.97 0 01-4.132 5.411M6.1 6.1A9.955 9.955 0 002.458 12c1.274 4.056 5.065 7 9.542 7 1.258 0 2.462-.244 3.563-.689" />
                </svg>
            </button>
        </div>
    </div>
</div>
