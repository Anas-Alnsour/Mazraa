<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Http\Requests\StoreSupplyRequest;
use App\Http\Requests\UpdateSupplyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplyItemController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'supply_company') abort(403);

        $supplies = Supply::where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('supplies.items.index', compact('supplies'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'supply_company') abort(403);

        return view('supplies.items.create');
    }

    public function store(StoreSupplyRequest $request)
    {
        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('supplies/images', 'public');
        }

        Supply::create([
            'company_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
        ]);

        return redirect()->route('supplies.items.index')
            ->with('success', 'Supply item added successfully to your inventory.');
    }

    public function edit(Supply $item)
    {
        if ($item->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('supplies.items.edit', compact('item'));
    }

    public function update(UpdateSupplyRequest $request, Supply $item)
    {
        if ($item->company_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('supplies/images', 'public');
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

        $item->delete();

        return redirect()->route('supplies.items.index')
            ->with('success', 'Supply item removed from inventory.');
    }
}
