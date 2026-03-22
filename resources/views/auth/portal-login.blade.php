<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mazraa Portal | Partner Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f9f8f4] min-h-screen flex items-center justify-center font-sans p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-[#1d5c42] tracking-tighter">Mazraa<span class="text-[#b46146]">.com</span></h1>
            <p class="text-sm font-bold text-gray-500 tracking-widest uppercase mt-2">B2B Partner Portal</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-2xl border border-gray-100 p-8">
            <h2 class="text-2xl font-black text-gray-900 mb-6 text-center">Welcome Back, Partner</h2>

            @if($errors->any())
                <div class="mb-6 bg-red-50 text-red-600 font-bold text-xs p-4 rounded-xl border border-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('portal.login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-gray-50 border-none rounded-xl py-4 px-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#1d5c42] transition-all outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full bg-gray-50 border-none rounded-xl py-4 px-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#1d5c42] transition-all outline-none">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#1d5c42] shadow-sm focus:ring-[#1d5c42]">
                        <span class="ml-2 text-xs font-bold text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#183126] hover:bg-[#10231b] text-white font-black py-4 rounded-xl shadow-lg transition-all transform active:scale-95 text-sm uppercase tracking-widest mt-4">
                    Secure Login
                </button>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-xs font-bold text-gray-400 mb-2">Are you a customer?</p>
                <a href="{{ route('login') }}" class="text-xs font-black text-[#b46146] hover:underline uppercase tracking-widest">Go to Customer Login</a>
            </div>
        </div>
    </div>

</body>
</html>
