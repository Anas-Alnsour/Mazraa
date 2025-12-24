<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;

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
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
            'status' => 'string|max:50',
        ]);

        // حفظ البيانات
        ContactUs::create($request->only('name', 'email', 'message', 'status'));
        // هنا يمكنك إرسال الإيميل أو حفظ الرسالة في قاعدة البيانات
        // مثال إرسال إيميل:
        // Mail::to('your-email@example.com')->send(new ContactMail($request->all()));

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }

    // Show Contact Messages to Admin
    public function showContactMessages()
    {
        $messages = ContactUs::latest()->paginate(8);

        return view('admin.contact.show', compact('messages'));;
    }

    public function destroy($id)
    {
    $message = ContactUs::findOrFail($id);
    $message->delete();

    return redirect()->route('admin.contact.show')->with('success', 'Message deleted successfully.');
    }

public function markAsRead($id)
    {
    $message = ContactUs::findOrFail($id);
    $message->status = 'read';
    $message->save();

    return redirect()->route('admin.contact.show')->with('success', 'Message marked as read.');
    }
}   