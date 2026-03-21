<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-white flex-col hidden md:flex">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-2xl font-bold text-emerald-400"><i class="fa-solid fa-leaf mr-2"></i>Mazraa Admin</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-slate-800 rounded-lg text-slate-300 font-medium transition-colors">
                <i class="fa-solid fa-chart-pie w-6"></i> <span class="ml-3">Dashboard</span>
            </a>
            <a href="{{ route('admin.verifications') }}" class="flex items-center p-3 hover:bg-slate-800 rounded-lg text-slate-300 font-medium transition-colors">
                <i class="fa-solid fa-clipboard-check w-6"></i> <span class="ml-3">Verifications</span>
            </a>
            <a href="{{ route('admin.system') }}" class="flex items-center p-3 bg-emerald-600 rounded-lg text-white font-medium transition-colors">
                <i class="fa-solid fa-cogs w-6"></i> <span class="ml-3">System Settings</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center p-3 hover:bg-red-600 rounded-lg text-slate-300 hover:text-white font-medium transition-colors">
                    <i class="fa-solid fa-sign-out-alt w-6"></i> <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">

        <header class="bg-white shadow-sm px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">System Management & Settings</h1>
        </header>

        <div class="p-6 space-y-8">

            @if(session('success'))
                <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm flex items-center">
                    <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
                        <span class="font-bold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc pl-10 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:w-1/2">
                <h2 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4 flex items-center">
                    <i class="fa-solid fa-percent mr-2 text-blue-500"></i> Global Settings
                </h2>

                <form method="POST" action="{{ route('admin.system.update') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="commission_rate" class="block text-sm font-bold text-gray-700 mb-2">Default Platform Commission Rate (%)</label>
                        <div class="flex items-center space-x-3">
                            <input type="number" step="0.01" min="0" max="100" id="commission_rate" name="commission_rate"
                                   value="{{ old('commission_rate', $defaultCommission) }}"
                                   class="block w-full sm:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm px-4 py-2 border bg-gray-50"
                                   placeholder="e.g. 10">

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-bold text-sm text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 ring-emerald-300 shadow transition-all">
                                <i class="fa-solid fa-save mr-2"></i> Update Rate
                            </button>
                        </div>
                        <p class="mt-3 text-xs text-gray-500 font-medium"><i class="fa-solid fa-circle-info mr-1 text-blue-400"></i> This rate applies to all new vendor bookings globally unless overridden specifically.</p>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fa-solid fa-users-gear mr-2 text-purple-500"></i> User Directory
                    </h2>
                    <span class="bg-purple-100 text-purple-700 py-1 px-3 rounded-full text-xs font-bold">{{ $users->count() }} Total Users</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Phone</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ $user->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold mr-3 shadow-inner">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-gray-800">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-red-100 text-red-800 border border-red-200',
                                                'user' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                                'farm_owner' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                                'supply_company' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                                'transport_company' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                'supply_driver' => 'bg-pink-100 text-pink-800 border border-pink-200',
                                                'transport_driver' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                            ];
                                            $badgeClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full shadow-sm {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', strtoupper($user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-gray-500 font-medium">{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fa-solid fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-medium">No users found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>
