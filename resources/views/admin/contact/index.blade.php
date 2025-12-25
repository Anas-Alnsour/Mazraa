@extends('layouts.app')

@section('title', 'Inbox Messages')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-green-800">Inbox Messages</h1>
            <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full border border-green-200">
                {{ $messages->total() }} Messages
            </span>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
            </div>
        @endif

        @if ($messages->isEmpty())
            <div class="flex flex-col items-center justify-center mt-20 space-y-4">
                <div class="bg-gray-100 p-6 rounded-full">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium text-lg">No message requests yet.</p>
            </div>
        @else
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Sender</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Message Preview</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-green-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($messages as $message)
                                <tr class="hover:bg-gray-50 transition duration-150 {{ $message->status === 'unread' ? 'bg-yellow-50/50' : '' }}">

                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold text-lg border border-green-200">
                                                {{ strtoupper(substr($message->name, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $message->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $message->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $message->message }}">
                                            {{ Str::limit($message->message, 60) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500">{{ $message->created_at->format('M d, Y') }}</span>
                                        <div class="text-xs text-gray-400">{{ $message->created_at->format('h:i A') }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($message->status === 'read')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                Read
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                                New
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-3">

                                            <a href="{{ route('admin.contact.show', $message->id) }}" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded-full transition" title="View Message">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            @if($message->status !== 'read')
                                            <form action="{{ route('admin.contact.markAsRead', $message->id) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900 p-1 hover:bg-green-50 rounded-full transition" title="Mark as Read">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            @endif

                                            <form action="{{ route('admin.contact.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded-full transition" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex justify-center pb-10">
                @if ($messages->hasPages())
                    <div class="bg-white shadow-md rounded-2xl px-4 py-2">
                        {{ $messages->links('pagination.green') }}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
