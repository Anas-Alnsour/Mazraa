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
use Illuminate\Support\Facades\DB;

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
                ->whereIn('status', ['completed', 'finished'])
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
                ->whereIn('status', ['completed', 'delivered'])
                ->exists();

            if (!$isVerified) {
                return back()->with('error', 'You can only review a transport company after completing a trip with them.');
            }
        }

        // 🚀 تفكيك قنبلة عدم التزامن: استخدام Transaction لضمان تحديث التقييم والمتوسط معاً
        try {
            DB::beginTransaction();

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

            // 🚀 تفكيك قنبلة التقييم الشبح: تحديث متوسط التقييم للكيان الأب
            $this->updateEntityAverageRating($modelClass, $entity->id);

            DB::commit();

            return back()->with('success', 'Thank you! Your verified review has been saved.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while saving your review. Please try again.');
        }
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $modelClass = $review->reviewable_type;
        $entityId = $review->reviewable_id;

        try {
            DB::beginTransaction();

            $review->delete();

            // 🚀 تحديث متوسط التقييم بعد الحذف لضمان دقة الأرقام في المنصة
            $this->updateEntityAverageRating($modelClass, $entityId);

            DB::commit();

            return back()->with('success', 'Review deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the review.');
        }
    }

    /**
     * دالة مساعدة لحساب وتحديث متوسط التقييمات بشكل فوري وآمن
     */
    private function updateEntityAverageRating($modelClass, $entityId)
    {
        // حساب المتوسط الحقيقي من الداتابيس (تجاهل التقييمات المحذوفة إذا كان هناك SoftDeletes)
        $average = Review::where('reviewable_type', $modelClass)
            ->where('reviewable_id', $entityId)
            ->avg('rating');

        // تقريب الرقم إلى منزلة عشرية واحدة (مثلاً 4.5)
        $formattedAverage = $average ? round($average, 1) : 0.0;

        // تحديث الكيان الأب
        $modelClass::where('id', $entityId)->update(['rating' => $formattedAverage]);
    }
}
