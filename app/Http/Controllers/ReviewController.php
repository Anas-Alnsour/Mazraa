<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'reviewable_id' => 'required|integer',
            'reviewable_type' => 'required|string|in:farm,supply_company,transport_company'
        ]);

        $modelClass = match($validated['reviewable_type']) {
            'farm' => Farm::class,
            'supply_company', 'transport_company' => User::class,
        };

        $entity = $modelClass::findOrFail($validated['reviewable_id']);

        if ($modelClass === User::class && $entity->role !== $validated['reviewable_type']) {
            abort(404, 'Entity not found.');
        }

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'reviewable_id' => $validated['reviewable_id'],
                'reviewable_type' => $modelClass,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return back()->with('success', 'Thank you! Your review has been saved.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
