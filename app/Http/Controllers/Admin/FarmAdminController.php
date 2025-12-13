<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use Illuminate\Support\Facades\Storage;

class FarmAdminController extends Controller
{

    public function index()
    {
        $farms = Farm::latest()->get();
        return view('admin.farms.index', compact('farms'));
    }


    public function create()
    {
        return view('admin.farms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'location' => 'required',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|numeric',
            'rating' => 'required|numeric|min:1|max:5',
            'description' => 'required',
            'main_image' => 'nullable|image|max:10240',
            'images.*' => 'image|max:10240',
        ]);

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('farms', 'public');
        }

        $farm = Farm::create($validated);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('farm_gallery', 'public');

                $farm->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        return redirect()->route('admin.farms.index')->with('success', 'Farm added successfully.');
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
            'name'            => 'required',
            'location'        => 'required',
            'price_per_night' => 'required|numeric',
            'capacity'        => 'required|numeric',
            'rating'          => 'required|numeric|min:1|max:5',
            'description'     => 'required',
            'main_image'      => 'nullable|image|max:10240',
            'images.*'        => 'nullable|image|max:10240',
        ]);

        $data = $validated;

        if ($request->boolean('remove_main_image')) {
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $data['main_image'] = null;
        } elseif ($request->hasFile('main_image')) {
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('farms', 'public');
        } else {
            $data['main_image'] = $farm->main_image;
        }

        $removeIds = $request->input('remove_gallery_images', []);
        if (!empty($removeIds)) {
            $imagesToDelete = $farm->images()->whereIn('id', $removeIds)->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('farm_gallery', 'public');

                $farm->images()->create([
                    'image_url' => $path,
                ]);
            }
        }

        $farm->update($data);

        return redirect()
            ->route('admin.farms.index')
            ->with('success', 'Farm updated successfully.');
    }

    public function destroy(Farm $farm)
    {
        if ($farm->main_image) {
            Storage::disk('public')->delete($farm->main_image);
        }

        foreach ($farm->images as $image) {
            Storage::disk('public')->delete($image->image_url);
            $image->delete();
        }

        $farm->delete();

        return redirect()->route('admin.farms.index')->with('success', 'Farm deleted successfully.');
    }
}
