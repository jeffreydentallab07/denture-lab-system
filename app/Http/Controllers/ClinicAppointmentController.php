<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class ClinicAppointmentController extends Controller
{
    public function index()
    {
        $clinic_id = Auth::guard('clinic')->user()->clinic_id;

        $appointments = Appointment::with(['technician', 'caseOrder'])
            ->whereHas('caseOrder', function ($query) use ($clinic_id) {
                $query->where('clinic_id', $clinic_id);
            })
            ->orderBy('schedule_datetime', 'desc')
            ->get();

        return view('clinic.appointments.index', compact('appointments'));
    }
}
