<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplyAdminController extends Controller
{
    /**
     * Display a listing of the supplies.
     */
    public function index()
    {
        $supplies = Supply::with('company')->latest()->paginate(10);
        return view('admin.supplies.index', compact('supplies'));
    }

    /**
     * Show the form for creating a new supply.
     */
    public function create()
    {
        $companies = User::where('role', 'supply_company')->get();
        return view('admin.supplies.create', compact('companies'));
    }

    /**
     * Store a newly created supply in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'  => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('supplies', 'public');
        }

        Supply::create($validated);

        return redirect()
            ->route('admin.supplies.index')
            ->with('success', 'Supply created successfully!');
    }

    /**
     * Show the form for editing the specified supply.
     */
    public function edit(Supply $supply)
    {
        $companies = User::where('role', 'supply_company')->get();
        return view('admin.supplies.edit', compact('supply', 'companies'));
    }

    /**
     * Update the specified supply in storage.
     */
    public function update(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'company_id'  => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($supply->image) {
                Storage::disk('public')->delete($supply->image);
            }
            $validated['image'] = $request->file('image')->store('supplies', 'public');
        }

        $supply->update($validated);

        return redirect()
            ->route('admin.supplies.index')
            ->with('success', 'Supply updated successfully!');
    }

    /**
     * Remove the specified supply from storage.
     */
    public function destroy(Supply $supply)
    {
        if ($supply->image) {
            Storage::disk('public')->delete($supply->image);
        }

        $supply->delete();

        return redirect()
            ->route('admin.supplies.index')
            ->with('success', 'Supply deleted successfully!');
    }
}
