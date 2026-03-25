@props([
    'reviews',        // The collection of existing reviews
    'reviewableId',   // The ID of the item being reviewed
    'reviewableType', // 'farm', 'supply_company', 'transport_company'
    'averageRating',  // Average rating calculated
])

<div class="mt-16 border-t border-gray-100 pt-10" id="reviews-section">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Customer Reviews</h2>
            <div class="flex items-center gap-2 mt-2">
                <div class="flex text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-sm font-bold text-gray-900">{{ number_format($averageRating, 1) }} out of 5</span>
                <span class="text-sm text-gray-500">({{ $reviews->count() }} reviews)</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            @auth
                @php
                    // Check if the current user already reviewed this item
                    $userReview = $reviews->where('user_id', Auth::id())->first();
                @endphp

                <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 sticky top-8 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $userReview ? 'Update Your Review' : 'Write a Review' }}</h3>
                    <p class="text-xs text-gray-500 mb-6">Share your experience to help others.</p>

                    <form action="{{ route('reviews.store') }}" method="POST" x-data="{ rating: {{ $userReview ? $userReview->rating : 0 }}, hoverRating: 0 }">
                        @csrf
                        <input type="hidden" name="reviewable_id" value="{{ $reviewableId }}">
                        <input type="hidden" name="reviewable_type" value="{{ $reviewableType }}">
                        <input type="hidden" name="rating" x-model="rating">

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Overall Rating</label>
                            <div class="flex items-center gap-1 cursor-pointer" @mouseleave="hoverRating = 0">
                                <template x-for="star in 5">
                                    <svg
                                        @click="rating = star"
                                        @mouseenter="hoverRating = star"
                                        :class="{'text-amber-400': (hoverRating || rating) >= star, 'text-gray-200': (hoverRating || rating) < star}"
                                        class="h-8 w-8 fill-current transition-colors transform hover:scale-110"
                                        viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </template>
                            </div>
                            @error('rating') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">Your Review (Optional)</label>
                            <textarea name="comment" id="comment" rows="4" placeholder="What did you like or dislike?"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none text-sm shadow-inner">{{ old('comment', $userReview->comment ?? '') }}</textarea>
                            @error('comment') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-4 rounded-xl shadow-md transition-all transform active:scale-95 text-sm uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed" :disabled="rating === 0">
                            {{ $userReview ? 'Update Review' : 'Post Review' }}
                        </button>
                    </form>

                    @if($userReview)
                        <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button class="w-full text-center text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition-colors py-3" onclick="return confirm('Are you sure you want to delete your review?');">
                                Delete my review
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 text-center sticky top-8 shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white mb-4 shadow-sm border border-gray-100">
                        <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">Login to Review</h3>
                    <p class="text-sm font-medium text-gray-500 mb-6">You must be logged into your account to share your experience with others.</p>
                    <a href="{{ route('login') }}" class="block w-full bg-gray-900 hover:bg-black text-white font-black py-3 px-4 rounded-xl shadow-lg transition-all transform active:scale-95 text-sm uppercase tracking-widest">
                        Log In Now
                    </a>
                </div>
            @endauth
        </div>

        <div class="lg:col-span-2">
            @if($reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($reviews->sortByDesc('created_at') as $review)
                        <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-lg">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-base">{{ $review->user->name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex text-amber-400 bg-amber-50 px-2 py-1 rounded-lg">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'fill-current' : 'text-amber-200 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>

                            @if($review->comment)
                                <div class="mt-4 pl-16">
                                    <p class="text-gray-600 text-sm font-medium leading-relaxed italic">"{{ $review->comment }}"</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-gray-50 rounded-[2.5rem] border border-gray-100 border-dashed">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white mb-6 shadow-sm border border-gray-100">
                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h4 class="text-2xl font-black text-gray-900 mb-2">No Reviews Yet</h4>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Be the first to share your experience!</p>
                </div>
            @endif
        </div>
    </div>
</div>
