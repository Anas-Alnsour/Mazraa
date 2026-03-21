<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Verifications - Super Admin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-2xl font-bold text-emerald-400"><i class="fa-solid fa-leaf mr-2"></i>Mazraa Admin</h2>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-slate-800 rounded-lg text-slate-300 font-medium transition-colors">
                <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
            </a>
            <a href="{{ route('admin.verifications') }}" class="flex items-center p-3 bg-emerald-600 rounded-lg text-white font-medium transition-colors">
                <i class="fa-solid fa-clipboard-check w-6"></i> Verifications
            </a>
            <a href="{{ route('admin.system') }}" class="flex items-center p-3 hover:bg-slate-800 rounded-lg text-slate-300 font-medium transition-colors">
                <i class="fa-solid fa-cogs w-6"></i> System Settings
            </a>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center p-3 hover:bg-red-600 rounded-lg text-slate-300 hover:text-white font-medium transition-colors">
                    <i class="fa-solid fa-sign-out-alt w-6"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">

        <!-- Top Header -->
        <header class="bg-white shadow-sm px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Pending Farm Verifications</h1>
        </header>

        <div class="p-6">

            <!-- Session Alerts -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-700">Farms Awaiting Approval</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-600 text-sm font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Farm Name</th>
                                <th class="px-6 py-4">Owner Name</th>
                                <th class="px-6 py-4">Location</th>
                                <th class="px-6 py-4">Submitted On</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                            @forelse($pendingFarms as $farm)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ $farm->id }}</td>
                                    <td class="px-6 py-4">{{ $farm->name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold mr-3">
                                                {{ strtoupper(substr($farm->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span>{{ $farm->user->name ?? 'Unknown Owner' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs">
                                        @if($farm->latitude && $farm->longitude)
                                            {{ $farm->latitude }}, {{ $farm->longitude }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $farm->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 flex justify-center space-x-2">
                                        <!-- Approve Form -->
                                        <form method="POST" action="{{ route('admin.verifications.handle', $farm->id) }}">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" onclick="return confirm('Are you sure you want to approve this farm?')" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded shadow-sm transition-colors">
                                                <i class="fa-solid fa-check mr-1"></i> Approve
                                            </button>
                                        </form>

                                        <!-- Reject Form -->
                                        <form method="POST" action="{{ route('admin.verifications.handle', $farm->id) }}">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" onclick="return confirm('Are you sure you want to reject this farm?')" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow-sm transition-colors">
                                                <i class="fa-solid fa-xmark mr-1"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-medium">No pending verifications</p>
                                        <p class="text-sm">All farms have been reviewed.</p>
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
