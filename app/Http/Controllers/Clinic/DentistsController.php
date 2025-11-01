<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DentistsController extends Controller
{
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();
        $dentists = Dentist::where('clinic_id', $clinic->clinic_id)->latest()->paginate(10);
        return view('clinic.dentists.index', compact('dentists'));
    }

    public function store(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:dentists,email',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('dentist_photos', 'public');
        }

        Dentist::create([
            'clinic_id' => $clinic->clinic_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'photo' => $validated['photo'] ?? null,
        ]);

        return redirect()->route('clinic.dentists.index')
            ->with('success', 'Dentist added successfully.');
    }

    public function update(Request $request, $id)
    {
        $clinic = Auth::guard('clinic')->user();

        $dentist = Dentist::where('clinic_id', $clinic->clinic_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:dentists,email,' . $id . ',dentist_id',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($dentist->photo) {
                Storage::disk('public')->delete($dentist->photo);
            }
            $validated['photo'] = $request->file('photo')->store('dentist_photos', 'public');
        }

        $dentist->update($validated);

        return redirect()->route('clinic.dentists.index')
            ->with('success', 'Dentist updated successfully.');
    }

    public function destroy($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $dentist = Dentist::where('clinic_id', $clinic->clinic_id)->findOrFail($id);

        if ($dentist->photo) {
            Storage::disk('public')->delete($dentist->photo);
        }

        $dentist->delete();

        return redirect()->route('clinic.dentists.index')
            ->with('success', 'Dentist deleted successfully.');
    }
}
