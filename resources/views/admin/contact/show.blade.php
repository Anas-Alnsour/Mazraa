@extends('layouts.app')

@section('title', 'View Message')

@section('content')
<div class="min-h-screen bg-gray-50 flex justify-center p-6 py-10">

    <div class="max-w-3xl w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 h-fit">

        <div class="bg-green-600 px-8 py-6 flex justify-between items-center text-white">
            <div class="flex items-center space-x-4">
                <div class="h-14 w-14 rounded-full bg-white text-green-700 flex items-center justify-center font-bold text-2xl shadow-sm border-2 border-green-100">
                    {{ strtoupper(substr($message->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $message->name }}</h2>
                    <a href="mailto:{{ $message->email }}" class="text-green-100 text-sm hover:underline">{{ $message->email }}</a>
                </div>
            </div>
            <div class="text-right">
                <p class="text-green-100 text-xs uppercase tracking-wide">Received on</p>
                <p class="font-semibold">{{ $message->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-green-200">{{ $message->created_at->format('h:i A') }}</p>
            </div>
        </div>

        <div class="p-8">
            <h3 class="text-gray-400 text-sm uppercase tracking-wide font-semibold mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                Message Content
            </h3>

            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 text-gray-800 leading-relaxed text-lg shadow-inner whitespace-pre-wrap break-words">
                {{ $message->message }}
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-between items-center">

            <a href="{{ route('admin.contact.index') }}" class="flex items-center text-gray-600 hover:text-green-700 font-semibold transition group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Messages
            </a>

            <div class="flex space-x-3">
                <form action="{{ route('admin.contact.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 font-medium transition shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>

                <a href="mailto:{{ $message->email }}" class="px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 font-medium shadow-md transition transform hover:scale-105 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Reply
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
