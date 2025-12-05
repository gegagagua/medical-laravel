<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with('patient:id,first_name,last_name')
            ->orderBy('date', 'desc')
            ->get();

        // Transform the data to match the Visit interface
        $visits = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'patientName' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'doctor_id' => $appointment->doctor_id,
                'doctorName' => $appointment->doctor_name,
                'department' => $appointment->department,
                'service' => $appointment->service,
                'service_id' => $appointment->service_id,
                'date' => $appointment->date->toISOString(),
                'time' => $appointment->time,
                'status' => $appointment->status,
                'notes' => $appointment->notes,
                'createdAt' => $appointment->created_at->toISOString(),
            ];
        });

        return response()->json($visits);
    }

    public function show($id)
    {
        $appointment = Appointment::with('patient')->findOrFail($id);

        return response()->json([
            'id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'patientName' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
            'doctor_id' => $appointment->doctor_id,
            'doctorName' => $appointment->doctor_name,
            'department' => $appointment->department,
            'service' => $appointment->service,
            'service_id' => $appointment->service_id,
            'date' => $appointment->date->toISOString(),
            'time' => $appointment->time,
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'createdAt' => $appointment->created_at->toISOString(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:users,id',
            'doctor_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'date' => 'required|date',
            'time' => 'required|string',
            'status' => 'sometimes|in:PENDING,CONFIRMED,CANCELLED,COMPLETED',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time into datetime
        $dateTime = $validated['date'] . ' ' . $validated['time'];
        
        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'] ?? null,
            'doctor_name' => $validated['doctor_name'],
            'department' => $validated['department'],
            'service' => $validated['service'],
            'service_id' => $validated['service_id'] ?? null,
            'date' => $dateTime,
            'time' => $validated['time'],
            'status' => $validated['status'] ?? 'PENDING',
            'notes' => $validated['notes'] ?? null,
        ]);

        $appointment->load('patient:id,first_name,last_name');

        return response()->json([
            'id' => $appointment->id,
            'patientName' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
            'doctorName' => $appointment->doctor_name,
            'department' => $appointment->department,
            'service' => $appointment->service,
            'service_id' => $appointment->service_id,
            'date' => $appointment->date->toISOString(),
            'time' => $appointment->time,
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'message' => 'Visit created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED,COMPLETED',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Visit status updated successfully',
            'visit' => $appointment,
        ]);
    }
}
