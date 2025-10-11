<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dentist;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DentistsController extends Controller
{
    public function index()
    {
        $dentists = Dentist::with('clinic')->latest()->paginate(15);
        return view('admin.dentists.index', compact('dentists'));
    }

    public function create()
    {
        $clinics = Clinic::all();
        return view('admin.dentists.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,clinic_id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:dentists,email',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('dentist_photos', 'public');
        }

        Dentist::create($validated);

        return redirect()->route('admin.dentists.index')
            ->with('success', 'Dentist added successfully.');
    }

    public function edit(Dentist $dentist)
    {
        $clinics = Clinic::all();
        return view('admin.dentists.edit', compact('dentist', 'clinics'));
    }

    public function update(Request $request, Dentist $dentist)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,clinic_id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:dentists,email,' . $dentist->dentist_id . ',dentist_id',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($dentist->photo) {
                Storage::disk('public')->delete($dentist->photo);
            }
            $validated['photo'] = $request->file('photo')->store('dentist_photos', 'public');
        }

        $dentist->update($validated);

        return redirect()->route('admin.dentists.index')
            ->with('success', 'Dentist updated successfully.');
    }

    public function destroy(Dentist $dentist)
    {
        if ($dentist->photo) {
            Storage::disk('public')->delete($dentist->photo);
        }

        $dentist->delete();

        return redirect()->route('admin.dentists.index')
            ->with('success', 'Dentist deleted successfully.');
    }
}
