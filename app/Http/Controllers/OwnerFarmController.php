<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use App\Http\Requests\StoreFarmRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class OwnerFarmController extends Controller
{
    /**
     * عرض قائمة مزارع المالك
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        $farms = Farm::where('owner_id', $user->id)
            ->with('images')
            ->latest()
            ->paginate(10);

        return view('owner.farms.index', compact('farms'));
    }

    /**
     * عرض فورم إضافة مزرعة جديدة
     */
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
    public function store(StoreFarmRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Upload the main cover image securely to the public disk
        $mainImagePath = $request->file('main_image')->store('farms/covers', 'public');

        // 2. Insert the Farm into the database
        // Automatically set owner_id, pending status, and unapproved state
        $farm = Farm::create([
            'name'                      => $validated['name'],
            'description'               => $validated['description'],
            'governorate'               => $validated['governorate'],
            'location_link'             => $validated['location_link'] ?? null,
            'capacity'                  => $validated['capacity'],
            'price_per_morning_shift'   => $validated['price_per_morning_shift'],
            'price_per_evening_shift'   => $validated['price_per_evening_shift'],
            'price_per_full_day'        => $validated['price_per_full_day'],
            'main_image'                => $mainImagePath,
            'owner_id'                  => Auth::id(),
            'status'                    => 'pending',
            'is_approved'               => false,
            'rating'                    => 0,
            'commission_rate'           => 10, // Default 10% platform commission
        ]);

        // 3. Handle Multiple Gallery Images
        if ($request->hasFile('gallery')) {
            $galleryImages = [];
            foreach ($request->file('gallery') as $file) {
                // Store each image securely
                $galleryPath = $file->store('farms/gallery', 'public');

                $galleryImages[] = [
                    'farm_id'    => $farm->id,
                    'image_url'  => $galleryPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Bulk insert for performance optimization
            FarmImage::insert($galleryImages);
        }

        // 4. Redirect with Success Flash Message
        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm created successfully! It is now pending admin approval.');
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
     * (ملاحظة: يمكنك لاحقاً تحويل هذا الـ Validation إلى UpdateFarmRequest إذا أردت)
     */
    public function update(Request $request, Farm $farm)
    {
        if ($farm->owner_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'description'               => 'required|string',
            'governorate'               => 'required|string|max:255',
            'location_link'             => 'nullable|url|max:255',
            'capacity'                  => 'required|integer|min:1',
            'price_per_morning_shift'   => 'required|numeric|min:0',
            'price_per_evening_shift'   => 'required|numeric|min:0',
            'price_per_full_day'        => 'required|numeric|min:0',
            'image'                     => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // الغلاف الجديد
            'gallery.*'                 => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // صور المعرض الجديدة
            'delete_images'             => 'nullable|array', // مصفوفة لصور يراد حذفها
        ]);

        // 1. تحديث البيانات الأساسية وصورة الغلاف
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
                FarmImage::create([
                    'farm_id'   => $farm->id,
                    'image_url' => $path
                ]);
            }
        }

        return redirect()->route('owner.farms.index')->with('success', 'Farm updated successfully!');
    }

    /**
     * حذف المزرعة
     */
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
            ->with('success', 'Farm and all its data have been deleted.');
    }
}
