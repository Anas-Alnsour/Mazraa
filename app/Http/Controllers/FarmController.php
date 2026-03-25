<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FarmController extends Controller
{
    /**
     * واجهة التصفح العامة للزبائن (Explore Page)
     */
    public function index(Request $request)
    {
        // عرض المزارع التي تمت الموافقة عليها فقط من قبل الأدمن
        $query = Farm::where('is_approved', true);

        // فلترة حسب الموقع
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // فلترة حسب أقل سعر
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        // فلترة حسب أعلى سعر
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        // فلترة حسب السعة (عدد الأشخاص)
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        $farms = $query->with('images')->latest()->paginate(12)->withQueryString();

        return view('public_farms.explore', compact('farms'));
    }

    /**
     * عرض صفحة تفاصيل مزرعة محددة للزبون
     */
    public function show(Farm $farm)
    {
        // جلب المزرعة مع صورها، حجوزاتها، التواريخ المحجوبة، والتقييمات مع أصحابها ⭐️
        $farm->load(['images', 'bookings', 'blockedDates', 'owner', 'reviews.user']);

        return view('public_farms.show', compact('farm'));
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

        // 1. التحقق من البيانات المدخلة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // صورة الغلاف
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // صور المعرض
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 2. رفع وحفظ الصورة الرئيسية (Main Cover)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('farms/covers', 'public');
            $validated['main_image'] = $path;
        }

        // 3. إعدادات افتراضية للمزرعة الجديدة
        $validated['owner_id'] = $user->id;
        $validated['status'] = 'pending'; // الحالة الافتراضية
        $validated['is_approved'] = false; // بانتظار موافقة الأدمن لتظهر في Explore
        $validated['commission_rate'] = 10;
        $validated['rating'] = 0.0;

        // 4. إنشاء سجل المزرعة
        $farm = Farm::create($validated);

        // 5. رفع وحفظ صور المعرض (Gallery) إن وجدت
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('farms/gallery', 'public');
                $FarmImage::create([
                    'farm_id' => $farm->id,
                    'image_url' => $galleryPath
                ]);
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

        // حذف الملفات الفيزيائية من التخزين قبل حذف البيانات
        if ($farm->main_image) {
            Storage::disk('public')->delete($farm->main_image);
        }

        foreach ($farm->images as $img) {
            Storage::disk('public')->delete($img->image_url);
        }

        $farm->delete();

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm and all its data have been deleted.');
    }
}
