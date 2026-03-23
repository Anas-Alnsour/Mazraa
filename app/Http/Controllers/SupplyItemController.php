<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplyItemController extends Controller
{
    public function index()
    {
        $supplies = Supply::where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('supplies.items.index', compact('supplies'));
    }

    public function create()
    {
        return view('supplies.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('supplies', 'public');
        }

        Supply::create([
            'company_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
        ]);

        return redirect()->route('supplies.items.index')
            ->with('success', 'Supply item added successfully.');
    }

    public function edit(Supply $item)
    {
        if ($item->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('supplies.items.edit', compact('item'));
    }

    public function update(Request $request, Supply $item)
    {
        if ($item->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('supplies', 'public');
        }

        $item->update($validated);

        return redirect()->route('supplies.items.index')
            ->with('success', 'Supply item updated successfully.');
    }

    public function destroy(Supply $item)
    {
        if ($item->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('supplies.items.index')
            ->with('success', 'Supply item deleted successfully.');
    }
}
