<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;

class FarmController extends Controller
{
    public function index(Request $request)
    {

        $query = Farm::query();

        // البحث حسب الاسم
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // البحث حسب الموقع
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // التصفية حسب السعر
        if ($request->filled('price_sort')) {
            $query->orderBy('price_per_night', $request->price_sort);
        }

        // التصفية حسب التقييم
        if ($request->filled('rating_sort')) {
            $query->orderBy('rating', $request->rating_sort);
        }

        // التعديل هنا: استخدام paginate(9) بدلاً من get()
        $farms = $query->paginate(9);

        return view('explore', compact('farms'));
    }

    public function show(Farm $farm)
    {
        // يمكن إضافة أي بيانات إضافية لاحقاً (مثل availability أو rooms)
        $farm->load('images', 'bookings'); // تحميل الصور والحجوزات
        return view('farm-details', compact('farm'));
    }
};
