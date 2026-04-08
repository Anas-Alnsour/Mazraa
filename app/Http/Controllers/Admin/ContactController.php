<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    // عرض جدول الرسائل
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);

        return view('admin.contacts.index', compact('messages'));
    }

    // عرض تفاصيل الرسالة
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // علم الرسالة كمقروءة بمجرد فتحها
        if($message->status !== 'read') {
            $message->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('message'));
    }

    // حذف الرسالة
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted successfully.');
    }

    // تعليم كـ مقروء يدوياً
    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => 'read']);

        return back()->with('success', 'Message marked as read.');
    }
}
