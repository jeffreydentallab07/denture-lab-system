<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    public function index()
    {
        // This query seems incorrect. Assuming you want to display a list of all clinics.
        $clinics = Clinic::all();
        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('admin.clinics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'clinic_id'      => 'required|integer',
            'clinic_name'    => 'required|string|max:255',
            'address'        => 'required|string',
            'contact_number' => 'required|string',
            'email'          => 'required|email',
        ]);

        Clinic::create($request->all());

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinic added successfully.');
    }

    public function edit(Clinic $clinic)
    {
        return response()->json($clinic);
    }

    public function update(Request $request, Clinic $clinic)
    {
        $request->validate([
            'clinic_name'    => 'required|string|max:255',
            'address'        => 'required|string',
            'contact_number' => 'required|string',
            'email'          => 'required|email',
        ]);

        $clinic->update($request->all());

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinic updated successfully.');
    }

    public function destroy(Clinic $clinic)
    {
        $clinic->delete();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinic deleted successfully.');
    }

    public function patients()
    {
        $clinicId = Auth::guard('clinic')->id();

        $patients = Patient::whereIn('dentist_id', function ($query) use ($clinicId) {
            $query->select('dentist_id')
                ->from('dentists')
                ->where('clinic_id', $clinicId);
        })->get();

        return view('clinic_patients.index', compact('patients'));
    }

    public function dentists()
    {
        $clinicId = Auth::guard('clinic')->id();
        $dentists = Dentist::where('clinic_id', $clinicId)->get();

        return view('clinic_dentists', compact('dentists'));
    }

    public function settings()
    {
        $clinic = Auth::guard('clinic')->user();
        return view('clinic.settings', compact('clinic'));
    }

    public function updateSettings(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $request->validate([
            'email'        => 'required|email|unique:clinics,email,' . $clinic->clinic_id . ',clinic_id',
            'clinic_name'  => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/clinic_photos', $filename); // save to storage/app/public/clinic_photos
            $clinic->profile_photo = 'clinic_photos/' . $filename; // relative path sa DB
        }

        // Update other fields
        $clinic->clinic_name    = $request->clinic_name;
        $clinic->email          = $request->email;
        $clinic->contact_number = $request->contact_number;
        $clinic->address        = $request->address;

        $clinic->save();

        return redirect()->route('clinic.dashboard')
            ->with('success', 'Settings updated successfully.');
    }
}
