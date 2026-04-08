<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;

class PageController extends Controller
{
    /**
     * Home page
     */
    public function home()
    {
        $featuredFarms = Farm::where('is_approved', true)
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();
            
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