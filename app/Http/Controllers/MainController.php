<?php

namespace App\Http\Controllers;

use App\Models\Appointment;

class MainController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function appointments()
    {
        $appointments = Appointment::with([
            'caseOrder',
            'material',
            'technician'
        ])->get();

        return view('appointments', compact('appointments'));
    }
}
