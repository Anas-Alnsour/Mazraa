@extends('layouts.app')

@section('title', 'Manage Farms')

@section('content')
    <div class="p-10">
        <h1 class="text-3xl font-bold text-green-700 mb-6">Manage Farms</h1>

        <a href="{{ route('admin.farms.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow-md">
            Add New Farm
        </a>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg border">
                <thead>
                    <tr class="text-left bg-gray-100 border-b">
                        <th class="py-3 px-4">Image</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Location</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Rating</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($farms as $farm)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">
                                
                                @if ($farm->main_image)
                                    <img src="{{ asset('storage/' . $farm->main_image) }}"
                                        class="h-14 w-20 rounded-md border mb-2 object-cover" alt="">
                                @endif

                                
                                @if ($farm->images->count())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($farm->images as $img)
                                            <img src="{{ asset('storage/' . $img->image_url) }}"
                                                class="h-10 w-10 rounded-md border object-cover" alt="">
                                        @endforeach
                                    </div>
                                @endif
                            </td>


                            <td class="py-2 px-4 font-semibold">{{ $farm->name }}</td>
                            <td class="py-2 px-4">{{ $farm->location }}</td>
                            <td class="py-2 px-4 text-green-700 font-bold">${{ $farm->price_per_night }}</td>
                            <td class="py-2 px-4">{{ $farm->rating }} ⭐</td>

                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('admin.farms.edit', $farm->id) }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md">
                                    Edit
                                </a>

                                <form action="{{ route('admin.farms.destroy', $farm->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md"
                                        onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No farms found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
