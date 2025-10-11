<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends Controller
{
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();

        $appointments = Appointment::with(['caseOrder', 'technician'])
            ->whereHas('caseOrder', function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->clinic_id);
            })
            ->latest('schedule_datetime')
            ->paginate(15);

        return view('clinic.appointments.index', compact('appointments'));
    }

    public function show($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $appointment = Appointment::with(['caseOrder.patient', 'technician', 'materialUsages.material', 'billing'])
            ->whereHas('caseOrder', function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->clinic_id);
            })
            ->findOrFail($id);

        return view('clinic.appointments.show', compact('appointment'));
    }
}
