<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Add New Farm') }}
            </h2>
            <a href="{{ route('owner.farms.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors text-sm flex items-center gap-2 border border-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Portfolio
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('owner.farms.store') }}">
                    @csrf

                    <!-- Section: Basic Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-6">Basic Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Farm Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                                    placeholder="e.g. Green Valley Retreat">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location / Address <span class="text-red-500">*</span></label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('location') border-red-500 @enderror"
                                    placeholder="e.g. Riyadh, Wadi Hanifa">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity (Guests) <span class="text-red-500">*</span></label>
                                <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" min="1" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('capacity') border-red-500 @enderror"
                                    placeholder="e.g. 15">
                                @error('capacity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-1">Price per Night (SAR) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night') }}" min="0" step="0.01" required
                                        class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('price_per_night') border-red-500 @enderror"
                                        placeholder="0.00">
                                </div>
                                @error('price_per_night')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Description & Media -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-6">Description & Media</h3>

                        <div class="space-y-6">
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Detailed Description <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="5" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror"
                                    placeholder="Describe your farm, its amenities, rules, and what makes it special...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Main Image URL -->
                            <div>
                                <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Main Image URL</label>
                                <input type="url" name="main_image" id="main_image" value="{{ old('main_image') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('main_image') border-red-500 @enderror"
                                    placeholder="https://example.com/image.jpg">
                                <p class="mt-1 text-xs text-gray-500">Provide a direct URL to an image. (File upload feature coming soon)</p>
                                @error('main_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Map Coordinates -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-6">Map Coordinates (Optional)</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Latitude -->
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="any"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('latitude') border-red-500 @enderror"
                                    placeholder="e.g. 24.7136">
                                @error('latitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="any"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('longitude') border-red-500 @enderror"
                                    placeholder="e.g. 46.6753">
                                @error('longitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end border-t border-gray-200 pt-6 gap-4">
                        <a href="{{ route('owner.farms.index') }}" class="text-gray-600 hover:text-gray-900 font-medium py-2 px-4 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Farm
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>
