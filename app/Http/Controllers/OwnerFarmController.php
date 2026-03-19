<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use Illuminate\Support\Facades\Auth;

class OwnerFarmController extends Controller
{
    /**
     * Display a listing of the owner's farms.
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure only farm owners can access this
        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        // Fetch farms exclusively owned by this user
        $farms = Farm::with('bookings')
            ->where('owner_id', $user->id)
            ->latest()
            ->paginate(10); // Using pagination for a clean UI

        return view('owner.farms.index', compact('farms'));
    }

    /**
     * Show the form for creating a new farm.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        return view('owner.farms.create');
    }

    /**
     * Store a newly created farm in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'main_image' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $validated['owner_id'] = $user->id;
        $validated['commission_rate'] = 10; // Default commission rate, could be configurable later

        // Generate a random rating for testing/initial purposes as they do not have ratings yet.
        $validated['rating'] = 0.0;

        Farm::create($validated);

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm successfully added to your portfolio.');
    }

    /**
     * Remove the specified farm from storage.
     */
    public function destroy(Farm $farm)
    {
        // Security check: ensure the user actually owns this farm before deleting
        if ($farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $farm->delete();

        return redirect()->route('owner.farms.index')
            ->with('success', 'Farm successfully deleted from your portfolio.');
    }
}
