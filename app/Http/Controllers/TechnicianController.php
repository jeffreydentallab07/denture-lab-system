<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Material;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class TechnicianController extends Controller
{
    // List all technicians
    public function index()
    {
        $technicians = User::where('role', 'technician')->get();
        return view('admin.technicians.index', compact('technicians'));
    }

    // Show form to create a technician
    public function create()
    {
        return view('admin.technicians.create');
    }

    // Store new technician
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255|regex:/^[A-Za-z\s.\-]+$/',
            'contact_number'  => 'required|regex:/^[0-9]{10,15}$/',
            'email'           => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password'        => 'required|string|min:6|confirmed',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos/users', 'public');
            }

            User::create([
                'name'           => strip_tags(trim($request->name)),
                'email'          => strtolower(strip_tags(trim($request->email))),
                'contact_number' => strip_tags(trim($request->contact_number)),
                'password'       => Hash::make($request->password),
                'role'           => 'technician',
                'photo'          => $photoPath,
            ]);

            DB::commit();
            return redirect()->route('technicians.index')->with('success', 'Technician added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create technician: ' . $e->getMessage()); // Log the error
            return redirect()->back()->withInput()->with('error', 'Failed to create technician. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::where('role', 'technician')->findOrFail($id);
        return view('admin.technicians.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'technician')->findOrFail($id);

        $request->validate([
            'name'           => 'required|string|regex:/^[A-Za-z\s.\-]+$/',
            'contact_number' => 'required|digits:10',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $photoPath = $request->file('photo')->store('photos/users', 'public');
                $user->photo = $photoPath;
            }

            $user->update([
                'name'           => strip_tags($request->name),
                'email'          => strtolower(strip_tags($request->email)),
                'contact_number' => strip_tags($request->contact_number),
                // 'photo' is updated above if a new file is uploaded
            ]);

            DB::commit();
            return redirect()->route('technicians.index')->with('success', 'Technician updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update technician: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update technician. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::where('role', 'technician')->findOrFail($id);

            // Delete associated photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->delete();

            DB::commit();
            return redirect()->route('technicians.index')->with('success', 'Technician deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete technician: ' . $e->getMessage());
            return redirect()->route('technicians.index')->with('error', 'Failed to delete technician.');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'technician') { // Check if user is logged in and is a technician
            abort(403, 'Unauthorized access.');
        }

        // Load appointments assigned to the technician with related clinic and material
        $appointments = Appointment::with(['caseOrder.clinic', 'material'])
            ->where('technician_id', $user->id)
            ->orderBy('schedule_datetime', 'desc')
            ->get();

        // Load all materials to populate the dropdowns
        $materials = Material::all();

        return view('technician.dashboard', compact('appointments', 'materials'));
    }

    public function updateAppointment(Request $request, $id)
    {
        // Log the incoming request for debugging
        Log::info('Technician update appointment request', [
            'appointment_id' => $id,
            'input' => $request->all(),
            'user_id' => Auth::id()
        ]);

        // Validate the request dynamically based on which field is present
        $rules = [];
        if ($request->has('work_status')) {
            $rules['work_status'] = ['required', 'string', 'in:in progress,finished'];
        }
        if ($request->has('material_id')) {
            // material_id can be null if "Select Material" is chosen
            $rules['material_id'] = ['nullable', 'exists:materials,material_id']; // Ensure 'materials' is your actual materials table name
        }

        // If neither field is present, it's an invalid request
        if (empty($rules)) {
            Log::warning('No valid update fields in request', ['appointment_id' => $id, 'input' => $request->all()]);
            return redirect()->back()->with('error', 'No valid update fields provided.');
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $appointment = Appointment::findOrFail($id);

            // Ensure the appointment belongs to the authenticated technician
            if ($appointment->technician_id !== Auth::id()) {
                Log::warning('Unauthorized technician attempted to update appointment', [
                    'appointment_id' => $id,
                    'user_id' => Auth::id(),
                    'appointment_technician_id' => $appointment->technician_id
                ]);
                return redirect()->route('technician.dashboard')
                    ->with('error', 'Unauthorized access to this appointment.');
            }

            // Update work status if provided
            if ($request->has('work_status')) {
                $appointment->work_status = $request->work_status;
                Log::info('Appointment work status updated by technician', [
                    'appointment_id' => $id,
                    'new_status' => $request->work_status,
                    'user_id' => Auth::id()
                ]);
            }

            // Update material if provided
            if ($request->has('material_id')) {
                $appointment->material_id = $request->material_id; // material_id can be null
                Log::info('Appointment material updated by technician', [
                    'appointment_id' => $id,
                    'new_material_id' => $request->material_id,
                    'user_id' => Auth::id()
                ]);
            }

            $appointment->save();

            DB::commit();

            $successMessage = 'Appointment updated successfully!';
            if ($request->has('work_status') && $request->work_status === 'finished') {
                $successMessage = 'Appointment marked as finished!';
            } elseif ($request->has('material_id')) {
                $successMessage = 'Material updated successfully!';
            } elseif ($request->has('work_status')) {
                $successMessage = 'Work status updated successfully!';
            }

            return redirect()->route('technician.dashboard')
                ->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed for technician appointment update', [
                'appointment_id' => $id,
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . implode(', ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update technician appointment: ' . $e->getMessage(), [
                'appointment_id' => $id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update appointment. Please try again. Error: ' . $e->getMessage());
        }
    }
}
