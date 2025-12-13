<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;

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
            'description' => 'nullable|string'
        ]);

        $name = $request->name;
        $stock = $request->quantity;
        $price = $request->price;
        $description = $request->description;

        $my_s = Supply::create([

            'name' => $name,
            'stock' => $stock,
            'price' => $price,
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
            'description' => 'nullable|string'
        ]);

        $supply->update([
            'name' => $request->name,
            'stock' => $request->quantity, // تحويل quantity إلى stock
            'price' => $request->price,
            'description' => $request->description,
        ]);
        return redirect()->route('admin.supplies.index')->with('success', 'Supply updated successfully!');
    }

     public function destroy(Supply $supply)
    {
        $supply->delete();
        return redirect()->route('admin.supplies.index')->with('success', 'Supply deleted successfully!');
    }

}
?>