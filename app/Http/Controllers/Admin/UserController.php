<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Update the specified user's role.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user,farm_owner,supply_company,transport_company,driver',
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->back()->with('success', "User role updated successfully to {$validated['role']}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', "You cannot delete your own admin account.");
        }

        $user->delete();

        return redirect()->back()->with('success', "User account deleted successfully.");
    }
}
