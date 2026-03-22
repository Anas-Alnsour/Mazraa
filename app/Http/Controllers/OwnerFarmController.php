<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerFarmController extends Controller
{
    /**
     * عرض قائمة مزارع المالك (بما فيها حالة الموافقة)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        $farms = Farm::where('owner_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('owner.farms.index', compact('farms'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'farm_owner') {
            abort(403);
        }
        return view('owner.farms.create');
    }

    /**
     * حفظ طلب إضافة مزرعة جديدة (توضع حالة الطلب Pending تلقائياً)
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // الغلاف
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // المعرض
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 1. رفع الصورة الرئيسية
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('farms/covers', 'public');
            $validated['main_image'] = $path;
        }

        // 2. إعدادات افتراضية (تمت إضافة is_approved للربط مع لوحة الأدمن)
        $validated['owner_id'] = $user->id;
        $validated['status'] = 'pending';
        $validated['is_approved'] = false; // 👈 التعديل اللي عملناه عشان شاشة الموافقات
        $validated['commission_rate'] = 10;
        $validated['rating'] = 0.0;

        $farm = Farm::create($validated);

        // 3. رفع الصور الفرعية (Gallery)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('farms/gallery', 'public');
                FarmImage::create([
                    'farm_id' => $farm->id,
                    'image_url' => $galleryPath
                ]);
            }
        }

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm request submitted! It will be live after Admin approval.');
    }

    /**
     * عرض صفحة التعديل
     */
    public function edit(Farm $farm)
    {
        // التحقق من الملكية
        if ($farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        return view('owner.farms.edit', compact('farm'));
    }

    /**
     * تحديث بيانات المزرعة
     */
    public function update(Request $request, Farm $farm)
    {
        if ($farm->owner_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120', // الغلاف الجديد
            'gallery.*' => 'nullable|image|max:5120', // صور المعرض الجديدة
            'delete_images' => 'nullable|array', // مصفوفة لصور يراد حذفها
        ]);

        // 1. تحديث البيانات الأساسية
        if ($request->hasFile('image')) {
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $validated['main_image'] = $request->file('image')->store('farms/covers', 'public');
        }
        $farm->update($validated);

        // 2. حذف الصور المختارة من المعرض
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $img = FarmImage::find($imageId);
                if ($img && $img->farm_id == $farm->id) {
                    Storage::disk('public')->delete($img->image_url);
                    $img->delete();
                }
            }
        }

        // 3. إضافة صور جديدة للمعرض
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('farms/gallery', 'public');
                $farm->images()->create(['image_url' => $path]);
            }
        }

        return redirect()->route('owner.farms.index')->with('success', 'Farm updated successfully!');
    }

    public function destroy(Farm $farm)
    {
        if ($farm->owner_id !== Auth::id()) {
            abort(403);
        }

        // حذف الصور من التخزين قبل حذف السجل
        if ($farm->main_image) {
            Storage::disk('public')->delete($farm->main_image);
        }
        foreach($farm->images as $img) {
            Storage::disk('public')->delete($img->image_url);
        }

        $farm->delete();

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm deleted successfully.');
    }
}
