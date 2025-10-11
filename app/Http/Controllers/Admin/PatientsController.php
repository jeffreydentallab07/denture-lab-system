<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\Dentist;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['clinic', 'dentist'])->latest()->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        $clinics = Clinic::all();
        $dentists = Dentist::all();
        return view('admin.patients.create', compact('clinics', 'dentists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,clinic_id',
            'dentist_id' => 'nullable|exists:dentists,dentist_id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Patient::create($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient added successfully.');
    }

    public function edit(Patient $patient)
    {
        $clinics = Clinic::all();
        $dentists = Dentist::all();
        return view('admin.patients.edit', compact('patient', 'clinics', 'dentists'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,clinic_id',
            'dentist_id' => 'nullable|exists:dentists,dentist_id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
