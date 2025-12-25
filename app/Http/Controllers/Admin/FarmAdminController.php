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
            'name'            => 'required|string|max:255',
            'location'        => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity'        => 'required|numeric|min:1',
            'rating'          => 'required|numeric|min:1|max:5',
            'description'     => 'required|string',
            'main_image'      => 'nullable|image|max:10240', // 10MB
            'images.*'        => 'image|max:10240',
        ]);

        // التعامل مع الصورة الرئيسية
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('farms', 'public');
        }

        // إنشاء المزرعة (نستثني مصفوفة الصور من البيانات المباشرة)
        $farmData = collect($validated)->except(['images', 'main_image'])->toArray();
        $farmData['main_image'] = $mainImagePath;

        $farm = Farm::create($farmData);

        // التعامل مع صور المعرض
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
            'name'            => 'required|string|max:255',
            'location'        => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity'        => 'required|numeric|min:1',
            'rating'          => 'required|numeric|min:1|max:5',
            'description'     => 'required|string',
            'main_image'      => 'nullable|image|max:10240',
            'images.*'        => 'nullable|image|max:10240',
        ]);

        // تحضير البيانات للتحديث (بدون الصور مؤقتاً)
        $data = collect($validated)->except(['images', 'main_image'])->toArray();

        // 1. التعامل مع الصورة الرئيسية (حذف أو استبدال)
        if ($request->boolean('remove_main_image')) {
            // إذا طلب المستخدم حذف الصورة
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $data['main_image'] = null;
        } elseif ($request->hasFile('main_image')) {
            // إذا رفع صورة جديدة، نحذف القديمة ونرفع الجديدة
            if ($farm->main_image) {
                Storage::disk('public')->delete($farm->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('farms', 'public');
        } else {
            // إبقاء الصورة القديمة
            $data['main_image'] = $farm->main_image;
        }

        // 2. تحديث بيانات المزرعة الأساسية
        $farm->update($data);

        // 3. حذف صور المعرض المحددة
        $removeIds = $request->input('remove_gallery_images', []);
        if (!empty($removeIds)) {
            $imagesToDelete = $farm->images()->whereIn('id', $removeIds)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }

        // 4. إضافة صور جديدة للمعرض
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('farm_gallery', 'public');
                $farm->images()->create([
                    'image_url' => $path,
                ]);
            }
        }

        return redirect()
            ->route('admin.farms.index')
            ->with('success', 'Farm updated successfully.');
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
