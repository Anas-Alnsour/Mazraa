<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farm;


class FavoriteController extends Controller
{
    // صفحة عرض المفضلات للمستخدم
    public function index()
    {
        $user = Auth::user();
        // جلب المزارع الموجودة بالمفضلات (مع علاقة farm)
        $favorites = $user->favorites()->with('images')->get();

        return view('favorites.index', compact('favorites'));
    }

    // إضافة للمفضلات
    public function store(Farm $farm)
    {
        $user = Auth::user();

        // تأكد أنها ليست موجودة مسبقاً
        if (! $user->favorites()->where('farm_id', $farm->id)->exists()) {
            $user->favorites()->attach($farm->id);
            return back()->with('success', 'Added to favorites.');
        }

        return back()->with('error', 'Already in favorites.');
    }

    // إزالة من المفضلات
    public function destroy(Farm $farm)
    {
        $user = Auth::user();

        if ($user->favorites()->where('farm_id', $farm->id)->exists()) {
            $user->favorites()->detach($farm->id);
            return back()->with('success', 'Removed from favorites.');
        }

        return back()->with('error', 'Favorite not found.');
    }
}