@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <header class="mb-10">
        <h1 class="text-4xl font-black text-white">System Management</h1>
        <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Global platform settings and user directory</p>
    </header>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-5 rounded-2xl shadow-sm mb-8 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-700 p-8">
                <h2 class="text-xl font-black text-white border-b border-slate-700 pb-4 mb-6">Global Settings</h2>
                <form method="POST" action="{{ route('admin.system.update') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Default Platform Commission (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $defaultCommission ?? 10) }}" class="block w-full rounded-2xl border-slate-700 bg-slate-900 text-white focus:border-emerald-500 focus:ring-emerald-500 px-5 py-4 font-bold">
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-black py-4 px-6 rounded-2xl hover:bg-emerald-700 transition-colors uppercase tracking-widest text-[10px]">
                        Update Settings
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-700 bg-slate-900/50 flex justify-between items-center">
                    <h2 class="text-lg font-black text-white">User Directory</h2>
                    <span class="bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 py-1 px-3 rounded-full text-[10px] font-black uppercase">{{ count($users ?? []) }} Total</span>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-700">User</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-700">Role</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-700">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @forelse($users ?? [] as $user)
                                <tr class="hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-white">{{ $user->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID: #{{ $user->id }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 inline-flex text-[9px] font-black uppercase tracking-widest rounded-lg bg-slate-700 text-slate-300 border border-slate-600">
                                            {{ str_replace('_', ' ', $user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-xs font-bold text-slate-400">{{ $user->email }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400 font-bold">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
