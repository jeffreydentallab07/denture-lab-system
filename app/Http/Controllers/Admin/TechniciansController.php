<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TechniciansController extends Controller
{
    public function index()
    {
        $technicians = User::where('role', 'technician')
            ->withCount('appointments')
            ->latest()
            ->paginate(15);

        return view('admin.technicians.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.technicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'contact_number' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'technician';

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('technician_photos', 'public');
        }

        User::create($validated);

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician added successfully.');
    }

    public function show($id)
    {
        $technician = User::where('role', 'technician')
            ->with(['appointments.caseOrder.clinic'])
            ->withCount('appointments')
            ->findOrFail($id);

        return view('admin.technicians.show', compact('technician'));
    }

    public function edit($id)
    {
        $technician = User::where('role', 'technician')->findOrFail($id);
        return view('admin.technicians.edit', compact('technician'));
    }

    public function update(Request $request, $id)
    {
        $technician = User::where('role', 'technician')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'contact_number' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($technician->photo && Storage::disk('public')->exists($technician->photo)) {
                Storage::disk('public')->delete($technician->photo);
            }

            $validated['photo'] = $request->file('photo')->store('technician_photos', 'public');
        }

        $technician->update($validated);

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician updated successfully.');
    }

    public function destroy($id)
    {
        $technician = User::where('role', 'technician')->findOrFail($id);

        // Check if technician has active appointments
        $activeAppointments = $technician->appointments()
            ->whereIn('work_status', ['pending', 'in-progress'])
            ->count();

        if ($activeAppointments > 0) {
            return redirect()->route('admin.technicians.index')
                ->with('error', 'Cannot delete technician with active appointments. Please reassign or complete them first.');
        }

        // Delete photo if exists
        if ($technician->photo && Storage::disk('public')->exists($technician->photo)) {
            Storage::disk('public')->delete($technician->photo);
        }

        $technician->delete();

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician deleted successfully.');
    }
}
