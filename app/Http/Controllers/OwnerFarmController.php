<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use App\Models\FarmBooking;
use App\Http\Requests\StoreFarmRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

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
            ->with('images') // 👈 جيدة لأنك تستخدم Paginate
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
        $farm = Farm::create([
            'name'                      => $validated['name'],
            'description'               => $validated['description'],
            'governorate'               => $validated['governorate'],
            'location'                  => $validated['location'],
            'location_link'             => $validated['location_link'] ?? null,
            'latitude'                  => $validated['latitude'] ?? $request->latitude,
            'longitude'                 => $validated['longitude'] ?? $request->longitude,
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
                $galleryPath = $file->store('farms/gallery', 'public');

                $galleryImages[] = [
                    'farm_id'    => $farm->id,
                    'image_url'  => $galleryPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            FarmImage::insert($galleryImages);
        }

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm created successfully! It is now pending admin approval.');
    }

    /**
     * عرض صفحة التعديل
     */
    public function edit(Farm $farm)
    {
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
            'name'                      => 'required|string|max:255',
            'description'               => 'required|string',
            'governorate'               => ['required', 'string', Rule::in(config('mazraa.governorates'))],
            'location_link'             => 'nullable|url',
            'latitude'                  => 'nullable|numeric',
            'longitude'                 => 'nullable|numeric',
            'capacity'                  => 'required|integer|min:1',
            'price_per_morning_shift'   => 'required|numeric|min:0',
            'price_per_evening_shift'   => 'required|numeric|min:0',
            'price_per_full_day'        => 'required|numeric|min:0',
            'main_image'                => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery.*'                 => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'delete_images'             => 'nullable|array',
        ]);

        // 1. تحديث البيانات الأساسية وصورة الغلاف
        if ($request->hasFile('main_image')) {
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('farms/covers', 'public');
        }

        $dataToUpdate = collect($validated)->except(['delete_images','gallery'])->toArray();
        // ضمان تخزين الإحداثيات إذا تم إرسالها من الخريطة
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $dataToUpdate['latitude'] = $request->latitude;
            $dataToUpdate['longitude'] = $request->longitude;
        }

        $farm->update($dataToUpdate);

        // 🚀 2. تفكيك قنبلة الـ N+1 Deletes (حذف جماعي للصور من التخزين والداتابيس)
        if ($request->has('delete_images')) {
            $imagesToDelete = FarmImage::whereIn('id', $request->delete_images)->where('farm_id', $farm->id)->get();
            $paths = $imagesToDelete->pluck('image_url')->toArray();

            if (!empty($paths)) {
                Storage::disk('public')->delete($paths); // حذف الملفات دفعة واحدة
            }

            FarmImage::whereIn('id', $request->delete_images)->where('farm_id', $farm->id)->delete(); // استعلام حذف واحد
        }

        // 🚀 3. تفكيك قنبلة الـ N+1 Inserts (إضافة جماعية للصور الجديدة)
        if ($request->hasFile('gallery')) {
            $newImages = [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('farms/gallery', 'public');
                $newImages[] = [
                    'farm_id'    => $farm->id,
                    'image_url'  => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if (!empty($newImages)) {
                FarmImage::insert($newImages); // استعلام إضافة واحد
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

        // =========================================================
        // 🚀 4. تفكيك قنبلة التدمير العشوائي: منع حذف مزرعة لها حجوزات نشطة
        // =========================================================
        $hasActiveBookings = FarmBooking::where('farm_id', $farm->id)
            ->whereIn('status', ['pending', 'pending_payment', 'confirmed'])
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->route('owner.farms.index')
                ->with('error', 'Cannot delete this farm because it has active or pending bookings. Please resolve them first.');
        }
        // =========================================================

        // 🚀 5. تسريع الحذف (Bulk Storage Deletion)
        $pathsToDelete = [];
        if ($farm->main_image) {
            $pathsToDelete[] = $farm->main_image;
        }

        if ($farm->images->isNotEmpty()) {
            $pathsToDelete = array_merge($pathsToDelete, $farm->images->pluck('image_url')->toArray());
        }

        if (!empty($pathsToDelete)) {
            Storage::disk('public')->delete($pathsToDelete);
        }

        $farm->delete();

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm and all its related images have been deleted successfully.');
    }
}
