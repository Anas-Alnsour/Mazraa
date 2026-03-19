<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;

class AdminFarmController extends Controller
{
    // عرض جميع الطلبات المعلقة للأدمن
    public function pending()
    {
        $farms = Farm::where('status', 'pending')->latest()->get();
        return view('admin.farms.pending', compact('farms'));
    }

    // زر الموافقة
    public function approve(Farm $farm)
    {
        $farm->update(['status' => 'active']);
        return back()->with('success', 'Farm approved and live on website!');
    }

    // زر الرفض
    public function reject(Farm $farm)
    {
        $farm->update(['status' => 'rejected']);
        return back()->with('error', 'Farm request rejected.');
    }
}
