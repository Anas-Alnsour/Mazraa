<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\User;
use App\Models\Review;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use App\Models\Transport;
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
            'reviewable_type' => 'required|string|in:farm,supply,transport_company'
        ]);

        $modelClass = match($validated['reviewable_type']) {
            'farm' => Farm::class,
            'supply' => \App\Models\Supply::class,
            'transport_company' => User::class,
        };

        $entity = $modelClass::findOrFail($validated['reviewable_id']);

        if ($modelClass === User::class && $entity->role !== $validated['reviewable_type']) {
            abort(404, 'Entity not found.');
        }

        $userId = Auth::id();

        // 🛡️ حماية: التأكد من أن المستخدم قد استخدم الخدمة فعلياً
        if ($validated['reviewable_type'] === 'farm') {
            $isVerified = FarmBooking::where('user_id', $userId)
                ->where('farm_id', $entity->id)
                ->whereIn('status', ['completed', 'finished']) // 👈 التعديل المعماري
                ->exists();

            if (!$isVerified) {
                return back()->with('error', 'You can only review a farm after completing a booking there.');
            }
        } elseif ($validated['reviewable_type'] === 'supply') {
            $isVerified = SupplyOrder::where('user_id', $userId)
                ->where('status', 'delivered')
                ->where('supply_id', $entity->id)
                ->exists();

            if (!$isVerified) {
                return back()->with('error', 'You can only review a supply item after receiving an order for it.');
            }
        } elseif ($validated['reviewable_type'] === 'transport_company') {
            $isVerified = Transport::where('user_id', $userId)
                ->where('company_id', $entity->id)
                ->whereIn('status', ['completed', 'delivered']) // 👈 التعديل المعماري
                ->exists();

            if (!$isVerified) {
                return back()->with('error', 'You can only review a transport company after completing a trip with them.');
            }
        }

        Review::updateOrCreate(
            [
                'user_id' => $userId,
                'reviewable_id' => $validated['reviewable_id'],
                'reviewable_type' => $modelClass,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return back()->with('success', 'Thank you! Your verified review has been saved.');
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
