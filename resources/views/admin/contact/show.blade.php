@extends('layouts.app')

@section('title', 'Contact Messages')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl text-green-700 font-bold mb-6 text-center">📩 Contact Messages</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($messages->isEmpty())
            <p class="text-gray-600 font-semibold text-center mt-20">No message requests yet.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-6 py-3 border-b font-semibold">Name</th>
                            <th class="px-6 py-3 border-b font-semibold">Email</th>
                            <th class="px-6 py-3 border-b font-semibold">Message</th>
                            <th class="px-6 py-3 border-b font-semibold">Date</th>
                            <th class="px-6 py-3 border-b font-semibold">Status</th>
                            <th class="px-6 py-3 border-b font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b">{{ $message->name }}</td>
                                <td class="px-6 py-4 border-b">{{ $message->email }}</td>
                                <td class="px-6 py-4 border-b">{{ Str::limit($message->message, 50) }}</td>
                                <td class="px-6 py-4 border-b">{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 border-b">
                                    @if ($message->status === 'read')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Read</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">New</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b flex space-x-2">
                                    <!-- زر تعليم الرسالة كمقروءة -->
                                    <form action="{{ route('contact.markAsRead', $message->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Mark as Read
                                        </button>
                                    </form>

                                    <!-- زر الحذف -->
                                    <form action="{{ route('contact.destroy', $message->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($messages->hasPages())
                <div class="mt-6 flex justify-end">
                    <div class="bg-white shadow-md rounded-2xl px-4 py-2">
                        {{ $messages->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @endif


        @endif
    </div>
@endsection
