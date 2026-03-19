<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerFarmController extends Controller
{
public function index()
{
    // جلب المزارع التي حالتها 'active' فقط لكي يراها الزوار
    $farms = Farm::where('status', 'active')->latest()->paginate(9);

    return view('farms.index', compact('farms'));
}

    public function create()
    {
        return view('owner.farms.create');
    }

    public function store(Request $request)
    {
        // 1. التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // الصورة الرئيسية
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // الصور الفرعية
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 2. رفع وحفظ الصورة الرئيسية (Cover)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('farms/covers', 'public');
            $validated['main_image'] = $path; // يتم حفظها في جدول farms
        }

        // 3. إضافة البيانات الإضافية
        $validated['owner_id'] = Auth::id();
        $validated['rating'] = 0.0;
        $validated['commission_rate'] = 10;

        // 4. إنشاء المزرعة في قاعدة البيانات
        $farm = Farm::create($validated);

        // 5. رفع وحفظ الصور الفرعية (Gallery) في جدول farm_images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('farms/gallery', 'public');

                // إنشاء سجل في جدول الصور الفرعية
                FarmImage::create([
                    'farm_id' => $farm->id,
                    'image_url' => $galleryPath
                ]);
            }
        }

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm and Gallery have been successfully listed!');
    }

    public function destroy(Farm $farm)
    {
        if ($farm->owner_id !== Auth::id()) {
            abort(403);
        }
        $farm->delete();
        return redirect()->route('owner.farms.index')->with('success', 'Farm deleted.');
    }
}
