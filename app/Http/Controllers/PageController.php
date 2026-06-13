<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * Home page
     */
    public function home()
    {
        // 🚀 1. تفكيك قنبلة الـ Database Hammer: استخدام Cache لمنع الضغط على السيرفر
        // 🚀 2. تفكيك قنبلة الـ N+1: تحميل الصور، المالك، وعدد التقييمات مسبقاً

        $featuredFarms = Cache::remember('home_featured_farms', now()->addMinutes(30), function () {
            return Farm::with(['images', 'owner'])
                ->withCount('reviews')
                ->where('is_approved', true)
                ->where('status', 'active')
                ->latest()
                ->take(6)
                ->get();
        });

        return view('home', compact('featuredFarms'));
    }

    /**
     * About Us page
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Contact Us page (GET)
     */
    public function contact()
    {
        return view('pages.contact');
    }
}
