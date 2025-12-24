<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuppliesAdmainController extends Controller
{
    public function index()
    {
        $supply = Supply::latest()->paginate(9);
        return view('admin.supplies.index', compact('supply'));
    }

    public function create(Supply $supply)
    {
        return view('admin.supplies.create', compact('supply'));
    }

    public function store(Request $request, Supply $supply)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:5120',
            'description' => 'nullable|string'
        ]);

        $imageName = $request->hasFile('image')
            ? $request->file('image')->store('supply_gallery', 'public')
            : null;

        $my_s = Supply::create([
            'name' => $request->name,
            'stock' => $request->quantity,
            'price' => $request->price,
            'image' => $imageName,
            'description' => $request->description
        ]);

        return back()->with('success', "A new supply : {$my_s->name}  with Quantity {$my_s->stock} and price {$my_s->price} was created successfully!");
    }

    public function edit($id)
    {
        $supply = Supply::findOrFail($id);
        return view('admin.supplies.edit', compact('supply'));
    }

    public function update(Request $request, Supply $supply)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'description' => 'nullable|string'
        ]);

        $imageName = $supply->image;
        if ($request->hasFile('image')) {
            if ($supply->image) {
                Storage::delete($supply->image);
            }
            $imageName = $request->file('image')->store('supply_gallery', 'public');
        }

        $supply->update([
            'name' => $request->name,
            'stock' => $request->quantity,
            'price' => $request->price,
            'image' => $imageName,
            'description' => $request->description,
        ]);
        return redirect()->route('admin.supplies.index')->with('success', 'Supply updated successfully!');
    }

    public function destroy(Supply $supply)
    {
        $supply->delete();
        return redirect()->route('admin.supplies.index')->with('success', 'Supply deleted successfully!');
    }
};
