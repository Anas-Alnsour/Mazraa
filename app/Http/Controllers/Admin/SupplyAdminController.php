<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplyAdminController extends Controller
{
    /**
     * Display a listing of the supplies.
     */
    public function index()
    {
        $supplies = Supply::latest()->paginate(10);
        return view('admin.supplies.index', compact('supplies'));
    }

    /**
     * Show the form for creating a new supply.
     */
    public function create()
    {
        return view('admin.supplies.create');
    }

    /**
     * Store a newly created supply in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'image'       => 'required|image|max:5120',
            'description' => 'nullable|string',
        ]);

        $imagePath = $request->file('image')->store('supply_gallery', 'public');

        Supply::create([
            'name'        => $request->name,
            'stock'       => $request->quantity,
            'price'       => $request->price,
            'image'       => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.supplies.index')
            ->with('success', 'Supply created successfully!');
    }

    /**
     * Show the form for editing the specified supply.
     */
    public function edit(Supply $supply)
    {
        return view('admin.supplies.edit', compact('supply'));
    }

    /**
     * Update the specified supply in storage.
     */
    public function update(Request $request, Supply $supply)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name'        => $request->name,
            'stock'       => $request->quantity,
            'price'       => $request->price,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($supply->image && Storage::disk('public')->exists($supply->image)) {
                Storage::disk('public')->delete($supply->image);
            }

            $data['image'] = $request->file('image')->store('supply_gallery', 'public');
        }

        $supply->update($data);

        return redirect()
            ->route('admin.supplies.index')
            ->with('success', 'Supply updated successfully!');
    }

    /**
     * Remove the specified supply from storage.
     */
    public function destroy(Supply $supply)
    {
        if ($supply->image && Storage::disk('public')->exists($supply->image)) {
            Storage::disk('public')->delete($supply->image);
        }

        $supply->delete();

        return redirect()
            ->route('admin.supplies.index')
            ->with('success', 'Supply deleted successfully!');
    }
}
