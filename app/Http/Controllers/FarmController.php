<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Supply;

class FarmController extends Controller
{
    /**
     * واجهة التصفح العامة للزبائن (Explore Page)
     */
    public function index(Request $request)
    {
        // عرض المزارع التي تمت الموافقة عليها وتكون نشطة
        $query = Farm::where('is_approved', true)->where('status', 'active');

        // فلترة حسب الاسم
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // فلترة حسب المحافظة
        if ($request->filled('governorate')) {
            $query->where('governorate', $request->governorate);
        }

        // فلترة حسب السعر لجميع أوقات الحجز
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $min = $request->min_price ?? 0;
            $max = $request->max_price ?? 999999;
            $query->where(function($q) use ($min, $max) {
                $q->whereBetween('price_per_morning_shift', [$min, $max])
                  ->orWhereBetween('price_per_evening_shift', [$min, $max])
                  ->orWhereBetween('price_per_full_day', [$min, $max]);
            });
        }

        // 🚀 تفكيك قنبلة الـ RAM: إزالة 'reviews.user' لمنع جلب آلاف التقييمات في صفحة التصفح!
        // نكتفي بجلب المالك والصور، مع سحب عدد التقييمات فقط من الداتابيس إذا أردت عرضها
        $farms = $query->with(['images', 'owner'])
            ->withCount('reviews') // 👈 تجلب رقم يمثل عدد التقييمات بدون سحب البيانات للرام
            ->latest()
            ->paginate(12)
            ->appends($request->all());

        return view('public_farms.explore', compact('farms'));
    }

    /**
     * عرض صفحة تفاصيل مزرعة محددة للزبون
     */
    public function show($id)
    {
        // 🚀 تفكيك قنبلة التفاصيل: جلب بيانات المزرعة الأساسية فقط
        $farm = Farm::with(['images', 'owner'])->findOrFail($id);

        // 🚀 حماية صفحة العرض: جلب التقييمات بشكل منفصل ومقسم لمنع انهيار الصفحة
        $reviews = $farm->reviews()->with('user')->latest()->paginate(5, ['*'], 'reviews_page');

        // 🚀 تفكيك قنبلة Supply::all() التي تسحب كل الداتابيس للرام
        $supplies = Supply::limit(50)->get();

        // تمرير المتغيرات للـ view (تأكد من تعديل الـ view لتستقبل $reviews كمتغير منفصل للـ pagination)
        return view('public_farms.show', compact('farm', 'supplies', 'reviews'));
    }

    /**
     * عرض صفحة إضافة مزرعة جديدة للمالك
     */
    public function create()
    {
        if (Auth::user()->role !== 'farm_owner') {
            abort(403, 'Unauthorized action.');
        }
        return view('owner.farms.create');
    }

    /**
     * حفظ المزرعة الجديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('farms/covers', 'public');
            $validated['main_image'] = $path;
        }

        $validated['owner_id'] = $user->id;
        $validated['status'] = 'pending';
        $validated['is_approved'] = false;
        $validated['commission_rate'] = 10;
        $validated['rating'] = 0.0;

        $farm = Farm::create($validated);

        if ($request->hasFile('gallery')) {
            $imagesData = [];
            $now = now();

            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('farms/gallery', 'public');
                // تجهيز المصفوفة
                $imagesData[] = [
                    'farm_id'    => $farm->id,
                    'image_url'  => $galleryPath,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 🚀 تفكيك قنبلة الـ N+1 Inserts: حقن كل الصور في قاعدة البيانات باستعلام واحد فقط!
            if (!empty($imagesData)) {
                FarmImage::insert($imagesData);
            }
        }

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm submitted successfully and is pending admin approval!');
    }

    /**
     * حذف المزرعة (للمالك فقط)
     */
    public function destroy(Farm $farm)
    {
        if ($farm->owner_id !== Auth::id()) {
            abort(403);
        }

        // 🚀 تسريع عملية الحذف: تجهيز مسارات كل الصور لحذفها دفعة واحدة من الـ Storage
        $pathsToDelete = [];

        if ($farm->main_image) {
            $pathsToDelete[] = $farm->main_image;
        }

        if ($farm->images->isNotEmpty()) {
            $pathsToDelete = array_merge($pathsToDelete, $farm->images->pluck('image_url')->toArray());
        }

        if (!empty($pathsToDelete)) {
            Storage::disk('public')->delete($pathsToDelete); // حذف كل الصور بأمر واحد
        }

        // حذف المزرعة (الصور المرتبطة في الداتابيس ستُحذف تلقائياً إذا كان هناك Cascade Delete)
        $farm->delete();

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm and all its data have been deleted.');
    }
}
