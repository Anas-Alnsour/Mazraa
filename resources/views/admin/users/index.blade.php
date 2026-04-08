@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-[fade-in_0.8s_ease-out]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/50 p-8 rounded-[2rem] border border-slate-700/50 backdrop-blur-xl">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter mb-2">Users & <span class="text-indigo-400">Roles</span></h1>
            <p class="text-slate-400 font-medium">Global access control and ecosystem member management.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-6 py-3 bg-slate-900/50 border border-slate-700 rounded-2xl">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Members</p>
                <p class="text-2xl font-black text-white">{{ $users->total() }}</p>
            </div>
            <div class="px-6 py-3 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl">
                <p class="text-[10px] font-black text-indigo-500/70 uppercase tracking-widest mb-1">Admins</p>
                <p class="text-2xl font-black text-indigo-400">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-slate-800/30 rounded-[2.5rem] border border-slate-700/50 overflow-hidden backdrop-blur-md shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">User Profile</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Auth Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Current Role</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Access Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @foreach($users as $user)
                        <tr class="group hover:bg-slate-700/30 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-slate-700 to-slate-600 flex items-center justify-center text-white font-black text-xl border border-slate-600 group-hover:border-indigo-500/50 transition-colors shadow-lg">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-white group-hover:text-indigo-400 transition-colors text-lg">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 font-medium">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-bold text-slate-300">Joined {{ $user->created_at->format('M Y') }}</span>
                                    <span class="text-[10px] text-slate-500 font-medium tracking-wider uppercase italic">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                        'farm_owner' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'supply_company' => 'bg-lime-500/10 text-lime-400 border-lime-500/20',
                                        'transport_company' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                        'driver' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'user' => 'bg-slate-700 text-slate-400 border-slate-600',
                                    ];
                                    $color = $roleColors[$user->role] ?? 'bg-slate-700 text-slate-400 border-slate-600';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full {{ $color }} text-[10px] font-black uppercase tracking-widest border shadow-sm">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                    {{-- Role Switch Form --}}
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                                            <option disabled selected>Change Role</option>
                                            <option value="admin">Admin</option>
                                            <option value="farm_owner">Farm Owner</option>
                                            <option value="supply_company">Supply Co.</option>
                                            <option value="transport_company">Transport Co.</option>
                                            <option value="driver">Driver</option>
                                            <option value="user">Regular User</option>
                                        </select>
                                    </form>

                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Archive/Delete this member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-all shadow-xl" title="Delete Permanent">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-8 py-8 bg-slate-800/50 border-t border-slate-700 flex justify-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
