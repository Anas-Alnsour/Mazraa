<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuppliesAdmainController extends Controller
{
    public function index()
    {
        $supply = Supply::latest()->get();
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
            'image' => 'required|image|max:2048',
            'description' => 'nullable|string'
        ]);

        $name = $request->name;
        $stock = $request->quantity;
        $price = $request->price;
        if ($request->hasFile('image')) {
            // تخزين الصورة داخل disk 'public' داخل المجلد supply_gallery
            // سيعيد المسار مثل: supply_gallery/filename.jpg
            $path = $request->file('image')->store('supply_gallery', 'public');
            $imageName = $path;
        } else {
            $imageName = null; // أو اسم صورة افتراضية إذا رغبت
        }
        $description = $request->description;

        $my_s = Supply::create([

            'name' => $name,
            'stock' => $stock,
            'price' => $price,
            'image' => $imageName,
            'description' => $description
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
            'image' => 'required|image|max:2048',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا موجودة
        if ($supply->image) {
            Storage::delete($supply->image);
        }
           // رفع الصورة الجديدة
        $data['image'] = $request->file('image')->store('supply_gallery', 'public');
    }
    $imageName = $data['image'] ?? $supply->image;

        $supply->update([
            'name' => $request->name,
            'stock' => $request->quantity, // تحويل quantity إلى stock
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
