<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // About Us page
    public function about()
    {
        return view('pages.about');
    }

    // Contact Us page (GET)
    public function contact()
    {
        return view('pages.contact');
    }

    // Contact Us form submission (POST)
    public function submitContact(Request $request)
    {
        // Validate form inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        // هنا يمكنك إرسال الإيميل أو حفظ الرسالة في قاعدة البيانات
        // مثال إرسال إيميل:
        // Mail::to('your-email@example.com')->send(new ContactMail($request->all()));

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }
}
