@extends('layouts.admin')

@section('title', 'Users & Roles Directory')

@section('content')
<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .user-row { transition: all 0.3s ease; }
    .user-row:hover { background: rgba(30, 41, 59, 0.8); transform: translateX(6px); border-color: rgba(99, 102, 241, 0.3); }
    /* Scrollbar for tables */
    .table-scroll::-webkit-scrollbar { height: 8px; }
    .table-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); border-radius: 8px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.8); border-radius: 8px; }
</style>

<div class="max-w-[96%] xl:max-w-7xl mx-auto py-8 space-y-8 pb-24">

    {{-- 🌟 Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/80 p-8 rounded-[2.5rem] border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden fade-in-up">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 mb-4 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span class="text-[10px] font-black tracking-widest text-indigo-400 uppercase">Identity Management</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">Users & <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Roles</span></h1>
            <p class="text-slate-400 font-medium text-sm">Global access control and ecosystem member management.</p>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="px-8 py-5 bg-slate-950/50 border border-slate-800 rounded-[2rem] text-center shadow-inner min-w-[140px]">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Total Members</p>
                <p class="text-3xl font-black text-white">{{ $users->total() }}</p>
            </div>
            <div class="px-8 py-5 bg-indigo-500/10 border border-indigo-500/20 rounded-[2rem] text-center shadow-[inset_0_0_20px_rgba(99,102,241,0.05)] min-w-[140px] relative overflow-hidden">
                <div class="absolute inset-0 bg-indigo-500/5 animate-pulse"></div>
                <p class="text-[9px] font-black text-indigo-400/80 uppercase tracking-widest mb-1.5 relative z-10">Admins Core</p>
                <p class="text-3xl font-black text-indigo-400 relative z-10">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 🌟 Users Directory Table (Fully Responsive via Overflow) --}}
    <div class="bg-slate-900/60 rounded-[3rem] border border-slate-800 overflow-hidden backdrop-blur-2xl shadow-2xl fade-in-up" style="animation-delay: 0.15s;">
        <div class="w-full overflow-x-auto table-scroll">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="border-b border-slate-800/80 bg-slate-950/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap w-1/3">Identity Profile</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] whitespace-nowrap w-1/4">Telemetry Status</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center whitespace-nowrap w-1/5">Clearance Level</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right whitespace-nowrap w-1/4">Access Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($users as $user)
                        <tr class="user-row group border-l-4 border-l-transparent hover:border-l-indigo-500">
                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-5">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-slate-800 to-slate-700 flex items-center justify-center text-white font-black text-xl border border-slate-600 shadow-lg group-hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] group-hover:border-indigo-500/50 transition-all shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-white group-hover:text-indigo-400 transition-colors text-base tracking-tight mb-0.5 truncate">{{ $user->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono flex items-center gap-1.5 truncate">
                                            <svg class="w-3 h-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="flex flex-col gap-2">
                                    <span class="text-xs font-bold text-slate-300">Joined {{ $user->created_at->format('M Y') }}</span>
                                    <span class="text-[9px] text-slate-500 font-black tracking-widest uppercase flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/80 shadow-[0_0_5px_rgba(16,185,129,0.8)]"></span>
                                        Active {{ $user->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-center whitespace-nowrap">
                                @php
                                    $roleConfig = [
                                        'admin' => ['bg-indigo-500/10', 'text-indigo-400', 'border-indigo-500/30', 'shadow-[0_0_15px_rgba(99,102,241,0.2)]'],
                                        'farm_owner' => ['bg-emerald-500/10', 'text-emerald-400', 'border-emerald-500/30', 'shadow-[0_0_15px_rgba(16,185,129,0.2)]'],
                                        'supply_company' => ['bg-lime-500/10', 'text-lime-400', 'border-lime-500/30', 'shadow-[0_0_15px_rgba(132,204,22,0.2)]'],
                                        'transport_company' => ['bg-cyan-500/10', 'text-cyan-400', 'border-cyan-500/30', 'shadow-[0_0_15px_rgba(6,182,212,0.2)]'],
                                        'driver' => ['bg-amber-500/10', 'text-amber-400', 'border-amber-500/30', 'shadow-[0_0_15px_rgba(245,158,11,0.2)]'],
                                        'user' => ['bg-slate-800', 'text-slate-400', 'border-slate-700', ''],
                                    ];
                                    $cfg = $roleConfig[$user->role] ?? $roleConfig['user'];
                                @endphp
                                <span class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border {{ $cfg[0] }} {{ $cfg[1] }} {{ $cfg[2] }} {{ $cfg[3] }}">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-10 py-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">

                                    {{-- Elegant Role Switch Form --}}
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="relative">
                                        @csrf @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="appearance-none bg-slate-950 border border-slate-700 text-slate-300 text-[9px] font-black uppercase tracking-widest rounded-xl pl-4 pr-10 py-3.5 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer hover:bg-slate-900 shadow-inner">
                                            <option disabled selected>Assign Role</option>
                                            <option value="admin">Admin Core</option>
                                            <option value="farm_owner">Farm Owner</option>
                                            <option value="supply_company">Supply Vendor</option>
                                            <option value="transport_company">Fleet Vendor</option>
                                            <option value="driver">Field Driver</option>
                                            <option value="user">Base User</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </form>

                                    {{-- Delete User (Protect Self) --}}
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Initiate protocol to permanently purge this identity?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-3.5 bg-slate-950 hover:bg-rose-600 text-slate-500 hover:text-white rounded-xl border border-slate-700 hover:border-rose-500 transition-all shadow-lg active:scale-95" title="Purge Identity">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @else
                                        <div class="p-3.5 bg-slate-950/50 text-slate-600 rounded-xl border border-slate-800 cursor-not-allowed" title="Cannot self-terminate">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center justify-center space-y-6">
                                    <div class="h-28 w-28 rounded-[2.5rem] bg-slate-950 flex items-center justify-center text-slate-700 border border-slate-800 shadow-inner">
                                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-3xl font-black text-white tracking-tight mb-2">Ecosystem Empty</h3>
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">No identities found matching the current database query.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-10 py-8 bg-slate-950/50 border-t border-slate-800 flex justify-center custom-pagination">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom Pagination Styling */
    .custom-pagination nav { @apply flex items-center gap-2; }
    .custom-pagination .page-link { @apply bg-slate-900 border border-slate-700 text-slate-400 text-[10px] font-black px-5 py-2.5 rounded-xl transition-all hover:bg-slate-800 hover:text-indigo-400 hover:border-indigo-500/30; }
    .custom-pagination .active .page-link { @apply bg-indigo-600 border-indigo-500 text-white shadow-[0_0_15px_rgba(99,102,241,0.3)]; }
</style>
@endsection
