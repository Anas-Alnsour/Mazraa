<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FarmAdminController extends Controller
{
    public function index()
    {
        // 🚀 Optimizing with Eager Loading
        $farms = Farm::with('owner')->latest()->paginate(10);
        return view('admin.farms.index', compact('farms'));
    }

    public function create()
    {
        return view('admin.farms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'governorate'               => ['required', 'string', Rule::in(config('mazraa.governorates'))],
            'location'                  => 'required|string|max:255',
            'location_link'             => 'nullable|url',
            'latitude'                  => 'required|numeric',
            'longitude'                 => 'required|numeric',
            'price_per_morning_shift'   => 'required|numeric|min:0',
            'price_per_evening_shift'   => 'required|numeric|min:0',
            'price_per_full_day'        => 'required|numeric|min:0',
            'capacity'                  => 'required|numeric|min:1',
            'rating'                    => 'required|numeric|min:0|max:5',
            'description'               => 'required|string',
            'main_image'                => 'nullable|image|max:10240',
            'status'                    => 'required|string|in:active,maintenance,suspended',
            'owner_id'                  => 'required|exists:users,id',
            'commission_rate'           => 'required|numeric|min:0|max:100',
            'is_approved'               => 'required|boolean',
            'images.*'                  => 'nullable|image|max:10240',
        ]);

        // Handle Main Image
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('farms/covers', 'public');
        }

        // Create Farm
        $farmData = collect($validated)->except(['images', 'main_image'])->toArray();
        $farmData['main_image'] = $mainImagePath;

        $farm = Farm::create($farmData);

        // 🚀 Bulk Insert for Gallery Images
        if ($request->hasFile('images')) {
            $galleryImages = [];
            foreach ($request->file('images') as $img) {
                $path = $img->store('farms/gallery', 'public');
                $galleryImages[] = [
                    'farm_id'    => $farm->id,
                    'image_url'  => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if (!empty($galleryImages)) {
                FarmImage::insert($galleryImages);
            }
        }

        return redirect()->route('admin.farms.index')->with('success', 'Farm created successfully.');
    }

    public function show(Farm $farm)
    {
        return view('admin.farms.show', compact('farm'));
    }

    public function edit(Farm $farm)
    {
        return view('admin.farms.edit', compact('farm'));
    }

    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'governorate'               => ['required', 'string', Rule::in(config('mazraa.governorates'))],
            'location'                  => 'required|string|max:255',
            'location_link'             => 'nullable|url',
            'latitude'                  => 'required|numeric',
            'longitude'                 => 'required|numeric',
            'price_per_morning_shift'   => 'required|numeric|min:0',
            'price_per_evening_shift'   => 'required|numeric|min:0',
            'price_per_full_day'        => 'required|numeric|min:0',
            'capacity'                  => 'required|numeric|min:1',
            'rating'                    => 'required|numeric|min:0|max:5',
            'description'               => 'required|string',
            'main_image'                => 'nullable|image|max:10240',
            'status'                    => 'required|string|in:active,maintenance,suspended',
            'owner_id'                  => 'required|exists:users,id',
            'commission_rate'           => 'required|numeric|min:0|max:100',
            'is_approved'               => 'required|boolean',
            'images.*'                  => 'nullable|image|max:10240',
        ]);

        $data = collect($validated)->except(['images', 'main_image'])->toArray();

        // Handle Main Image replacement
        if ($request->hasFile('main_image')) {
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('farms/covers', 'public');
        }

        $farm->update($data);

        // 🚀 Bulk Delete for Gallery Images
        $removeIds = $request->input('remove_gallery_images', []);
        if (!empty($removeIds)) {
            $imagesToDelete = FarmImage::whereIn('id', $removeIds)->where('farm_id', $farm->id)->get();
            $paths = $imagesToDelete->pluck('image_url')->toArray();

            if (!empty($paths)) {
                Storage::disk('public')->delete($paths);
            }

            FarmImage::whereIn('id', $removeIds)->where('farm_id', $farm->id)->delete();
        }

        // 🚀 Bulk Insert for new Gallery images
        if ($request->hasFile('images')) {
            $newImages = [];
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('farms/gallery', 'public');
                $newImages[] = [
                    'farm_id'    => $farm->id,
                    'image_url'  => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if (!empty($newImages)) {
                FarmImage::insert($newImages);
            }
        }

        return redirect()->route('admin.farms.index')->with('success', 'Farm updated successfully.');
    }

    public function destroy(Farm $farm)
    {
        // 🚀 منع الحذف العشوائي (Protect Active Bookings)
        $hasActiveBookings = FarmBooking::where('farm_id', $farm->id)
            ->whereIn('status', ['pending', 'pending_payment', 'confirmed'])
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->route('admin.farms.index')
                ->with('error', 'Cannot delete this farm because it has active or pending bookings. Please resolve them first.');
        }

        // 🚀 تسريع الحذف والتخلص من Storage Exhaustion
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

        // الحذف الفعلي للكيان
        $farm->delete();

        return redirect()->route('admin.farms.index')->with('success', 'Farm and all related media deleted successfully.');
    }
}
