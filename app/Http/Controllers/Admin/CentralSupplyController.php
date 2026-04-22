<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CentralSupplyController extends Controller
{
    public function index(Request $request)
    {
        $query = Supply::query();

        // 💡 تطبيق فلتر الأقسام
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // 💡 تقسيم 12 منتج للصفحة
        $supplies = $query->latest()->paginate(12);

        return view('admin.central_supply.index', compact('supplies'));
    }

    public function create()
    {
        return view('admin.central_supply.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        // ✅ التعديل هنا
        $data = $request->except(['_token', '_method', 'image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('supplies', 'public');
        }

        Supply::create($data);
        return redirect()->route('admin.central-supplies.index')->with('success', 'Global Product published.');
    }
    public function edit($id)
    {
        $supply = Supply::findOrFail($id);
        return view('admin.central_supply.edit', compact('supply'));
    }

    public function update(Request $request, $id)
    {
        $supply = Supply::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        // ✅ التعديل هنا
        $data = $request->except(['_token', '_method', 'image']);

        if ($request->hasFile('image')) {
            if ($supply->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($supply->image);
            }
            $data['image'] = $request->file('image')->store('supplies', 'public');
        }

        $supply->update($data);
        return redirect()->route('admin.central-supplies.index')->with('success', 'Global Product updated.');
    }
    public function destroy($id)
    {
        $supply = Supply::findOrFail($id);
        if ($supply->image) {
            Storage::disk('public')->delete($supply->image);
        }
        $supply->delete();
        return back()->with('success', 'Global Product removed.');
    }
}
