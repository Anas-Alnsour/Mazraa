@extends('layouts.app')

@section('title', 'Transport Details')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in-up">
    
    {{-- Top Navigation & Title --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-slate-600 transition-colors">Platform</a>
                <span>/</span>
                <a href="{{ route('transports.index') }}" class="hover:text-slate-600 transition-colors">Transports</a>
                <span>/</span>
                <span class="text-slate-900">Details</span>
            </nav>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Mission Briefing</h1>
            <p class="text-slate-500 font-bold mt-1 uppercase tracking-tighter">Reference #TRP-{{ str_pad($transport->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <a href="{{ route('transports.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white hover:bg-slate-50 text-slate-900 text-xs font-black uppercase tracking-widest rounded-2xl transition-all border border-slate-200 shadow-sm group">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Archives
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- Left Column: Core Trip Info --}}
        <div class="lg:col-span-2 space-y-10">
            
            {{-- Trip Status Hero --}}
            <div class="bg-white rounded-[3rem] p-10 shadow-[0_40px_80px_-15px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="flex items-center gap-8">
                    <div class="w-24 h-24 rounded-[2rem] bg-slate-900 flex items-center justify-center text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1-1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                             <span class="w-2 h-2 rounded-full {{ $transport->status === 'completed' ? 'bg-emerald-500' : ($transport->status === 'cancelled' ? 'bg-rose-500' : 'bg-amber-500 animate-pulse') }}"></span>
                             <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 font-bold capitalize">{{ $transport->status }}</span>
                        </div>
                        <h2 class="text-4xl font-black text-slate-900 tracking-tight mt-3">Ready for Pickup</h2>
                        <p class="text-slate-400 font-bold mt-1 italic tracking-tight">{{ $transport->transport_type }} service for {{ $transport->passengers }} passengers</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Net Total</p>
                    <p class="text-5xl font-black text-slate-900 tracking-tighter">{{ number_format($transport->price, 2) }} <span class="text-lg text-emerald-500 font-extrabold">JOD</span></p>
                </div>
            </div>

            {{-- Routing Logic --}}
            <div class="bg-white rounded-[3rem] p-12 shadow-xl border border-slate-100 flex flex-col md:flex-row gap-12">
                <div class="flex-1 space-y-12">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Logistics Path</h3>
                    
                    <div class="space-y-12 relative">
                        <div class="absolute left-4 top-2 bottom-2 w-0.5 border-l-2 border-slate-100 border-dashed"></div>
                        
                        <div class="flex items-start gap-8 relative z-10 transition-transform hover:translate-x-2">
                            <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-white ring-8 ring-white shadow-lg">
                                <span class="text-[10px] font-black">A</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Starting Point</p>
                                <p class="text-xl font-black text-slate-800 tracking-tight">{{ $transport->start_and_return_point ?: 'Custom Location' }}</p>
                                <p class="text-xs text-slate-400 font-bold mt-1 italic">{{ $transport->origin_governorate ?? 'Unknown Region' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-8 relative z-10 transition-transform hover:translate-x-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white ring-8 ring-white shadow-lg">
                                <span class="text-[10px] font-black">B</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Final Destination</p>
                                <p class="text-xl font-black text-slate-800 tracking-tight">{{ $transport->farm->name }}</p>
                                <p class="text-xs text-slate-400 font-bold mt-1 italic">{{ $transport->destination_governorate ?? $transport->farm->governorate ?? 'Selected Farm' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-px bg-slate-100 hidden md:block"></div>

                <div class="flex-none md:w-64 space-y-8">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Timeline</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:border-emerald-200 transition-colors">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none group-hover:text-emerald-500 font-bold">Pick up</p>
                            <p class="text-2xl font-black text-slate-900">{{ Carbon\Carbon::parse($transport->Farm_Arrival_Time)->format('H:i') }} <span class="text-xs font-bold text-slate-400 uppercase">{{ Carbon\Carbon::parse($transport->Farm_Arrival_Time)->format('A') }}</span></p>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:border-rose-200 transition-colors">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none group-hover:text-rose-500 font-bold">Return</p>
                            <p class="text-2xl font-black text-slate-900">
                                @if($transport->Farm_Departure_Time)
                                    {{ Carbon\Carbon::parse($transport->Farm_Departure_Time)->format('H:i') }} <span class="text-xs font-bold text-slate-400 uppercase">{{ Carbon\Carbon::parse($transport->Farm_Departure_Time)->format('A') }}</span>
                                @else
                                    <span class="text-slate-400">TBD</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Side Details --}}
        <div class="space-y-10">
            
            {{-- Assigned Captain --}}
            <div class="bg-gradient-to-br from-[#020617] to-[#1e293b] rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-bl-[5rem] translate-x-10 -translate-y-10 transition-transform group-hover:scale-125"></div>
                
                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-8 leading-none relative z-10 font-bold">Fleet Commander</h4>
                
                @if($transport->driver)
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-24 h-24 rounded-[2rem] bg-white/10 border border-white/10 flex items-center justify-center text-4xl mb-6 shadow-xl ring-8 ring-white/5">👨‍✈️</div>
                        <h5 class="text-2xl font-black tracking-tight leading-none mb-1">{{ $transport->driver->name }}</h5>
                        <p class="text-slate-400 font-bold text-sm">Professional Captain</p>
                        
                        <a href="tel:{{ $transport->driver->phone }}" class="mt-8 w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-emerald-500/20 transition-all flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Direct Line
                        </a>
                    </div>
                @else
                    <div class="relative z-10 py-6 text-center">
                        <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-500">
                             <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-400 italic">Our algorithm is currently finding the best pilot for your route.</p>
                    </div>
                @endif
            </div>

            {{-- Vehicle Info --}}
            <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none font-bold">Vehicle Specs</h4>
                    <span class="text-2xl">🚐</span>
                </div>

                @if($transport->vehicle)
                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-black text-slate-900 tracking-tight">{{ $transport->vehicle->name ?? $transport->vehicle->model ?? 'Standard Shuttle' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">Commercial Grade Fleet</p>
                        </div>
                        
                        <div class="flex items-center justify-between py-4 border-y border-slate-50">
                             <span class="text-[10px] font-black text-slate-400 uppercase font-bold">License</span>
                             <span class="bg-slate-900 text-white px-3 py-1 rounded-xl text-[10px] font-black tracking-widest">{{ $transport->vehicle->license_plate ?? 'PENDING' }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                             <span class="text-[10px] font-black text-slate-400 uppercase font-bold">Colorway</span>
                             @if(isset($transport->vehicle->color))
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-600 capitalize">{{ $transport->vehicle->color }}</span>
                                    <div class="w-4 h-4 rounded-full border border-slate-200 shadow-sm" style="background-color: {{ strtolower($transport->vehicle->color) }}"></div>
                                </div>
                             @else
                                <span class="text-[10px] font-bold text-slate-600">Company White</span>
                             @endif
                        </div>
                    </div>
                @else
                    <div class="py-4 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-inner">
                            <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Optimizing Pickup Path</p>
                    </div>
                @endif
            </div>

            {{-- Notes Context --}}
            @if($transport->notes)
                <div class="bg-amber-50 rounded-[3rem] p-10 border border-amber-100 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-6xl text-amber-500/5 font-black uppercase pointer-events-none font-black">Note</div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-4 leading-none font-bold">Operational Remarks</p>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed italic">"{{ $transport->notes }}"</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
