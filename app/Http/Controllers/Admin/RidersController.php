<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RidersController extends Controller
{
    public function index()
    {
        $riders = User::where('role', 'rider')
            ->withCount('deliveries')
            ->latest()
            ->paginate(15);

        return view('admin.riders.index', compact('riders'));
    }

    public function create()
    {
        return view('admin.riders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'contact_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'rider';

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('rider_photos', 'public');
        }

        User::create($validated);

        return redirect()->route('admin.riders.index')
            ->with('success', 'Rider added successfully.');
    }

    public function edit($id)
    {
        $rider = User::where('role', 'rider')->findOrFail($id);
        return view('admin.riders.edit', compact('rider'));
    }

    public function show($id)
    {
        $rider = User::where('role', 'rider')
            ->with(['deliveries.appointment.caseOrder.clinic'])
            ->withCount('deliveries')
            ->findOrFail($id);

        return view('admin.riders.show', compact('rider'));
    }

    public function update(Request $request, $id)
    {
        $rider = User::where('role', 'rider')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'contact_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($rider->photo) {
                Storage::disk('public')->delete($rider->photo);
            }
            $validated['photo'] = $request->file('photo')->store('rider_photos', 'public');
        }

        $rider->update($validated);

        return redirect()->route('admin.riders.index')
            ->with('success', 'Rider updated successfully.');
    }

    public function destroy($id)
    {
        $rider = User::where('role', 'rider')->findOrFail($id);

        if ($rider->photo) {
            Storage::disk('public')->delete($rider->photo);
        }

        $rider->delete();

        return redirect()->route('admin.riders.index')
            ->with('success', 'Rider deleted successfully.');
    }
}
