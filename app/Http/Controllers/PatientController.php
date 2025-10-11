<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Dentist;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::latest()->paginate(10);
        $dentists = Dentist::all();
        return view('clinic.patients.index', compact('patients', 'dentists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name'   => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'dentist_id' => 'nullable|exists:dentists,id',

        ]);

        Patient::create($request->all());

        return redirect()->route('clinic.patients.index')->with('success', 'Patient added successfully!');
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'patient_name'   => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'dentist_id' => 'nullable|exists:dentists,id',

        ]);

        $patient->update($request->all());

        return redirect()->route('clinic.patients.index')->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('clinic.patients.index')->with('success', 'Patient deleted successfully!');
    }
}
