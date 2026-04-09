<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FarmAdminController extends Controller
{
    public function index()
    {
        // التعديل الأساسي: استخدام paginate بدلاً من get
        $farms = Farm::latest()->paginate(10);
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
            'location_link'             => 'nullable|url|max:255',
            'latitude'                  => 'required|numeric',
            'longitude'                 => 'required|numeric',
            'price_per_night'           => 'required|numeric|min:0',
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

        // Handle Gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('farms/gallery', 'public');
                $farm->images()->create(['image_url' => $path]);
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
            'location_link'             => 'nullable|url|max:255',
            'latitude'                  => 'required|numeric',
            'longitude'                 => 'required|numeric',
            'price_per_night'           => 'required|numeric|min:0',
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

        // Handle Gallery removal
        $removeIds = $request->input('remove_gallery_images', []);
        if (!empty($removeIds)) {
            $imagesToDelete = $farm->images()->whereIn('id', $removeIds)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }

        // Handle new Gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('farms/gallery', 'public');
                $farm->images()->create(['image_url' => $path]);
            }
        }

        return redirect()->route('admin.farms.index')->with('success', 'Farm updated successfully.');
    }

    public function destroy(Farm $farm)
    {
        // حذف الصورة الرئيسية
        if ($farm->main_image) {
            Storage::disk('public')->delete($farm->main_image);
        }

        // حذف صور المعرض
        foreach ($farm->images as $image) {
            Storage::disk('public')->delete($image->image_url);
            $image->delete();
        }

        // حذف السجل
        $farm->delete();

        return redirect()->route('admin.farms.index')->with('success', 'Farm deleted successfully.');
    }
}
