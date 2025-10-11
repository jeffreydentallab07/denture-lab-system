<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientsController extends Controller
{
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();

        $patients = Patient::with('dentist')
            ->where('clinic_id', $clinic->clinic_id)
            ->latest()
            ->paginate(15);

        $dentists = Dentist::where('clinic_id', $clinic->clinic_id)->get();

        return view('clinic.patients.index', compact('patients', 'dentists'));
    }

    public function store(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'dentist_id' => 'required|exists:dentists,dentist_id',
        ]);

        Patient::create([
            'clinic_id' => $clinic->clinic_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'dentist_id' => $validated['dentist_id'],
        ]);

        return redirect()->route('clinic.patients.index')
            ->with('success', 'Patient added successfully.');
    }

    public function update(Request $request, $id)
    {
        $clinic = Auth::guard('clinic')->user();

        $patient = Patient::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'dentist_id' => 'required|exists:dentists,dentist_id',
        ]);

        $patient->update($validated);

        return redirect()->route('clinic.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $patient = Patient::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        $patient->delete();

        return redirect()->route('clinic.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
