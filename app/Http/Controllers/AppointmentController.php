<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with('user:id,first_name,last_name')
            ->orderBy('date', 'desc')
            ->get();

        // Transform the data to match the Visit interface
        $visits = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'patientName' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
                'doctorName' => $appointment->doctor_name,
                'department' => $appointment->department,
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
        $appointment = Appointment::with('user')->findOrFail($id);

        return response()->json([
            'id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'patientName' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
            'doctorName' => $appointment->doctor_name,
            'department' => $appointment->department,
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
            'user_id' => 'required|exists:users,id',
            'doctor_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'status' => 'sometimes|in:PENDING,CONFIRMED,CANCELLED,COMPLETED',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time into datetime
        $dateTime = $validated['date'] . ' ' . $validated['time'];
        
        $appointment = Appointment::create([
            'user_id' => $validated['user_id'],
            'doctor_name' => $validated['doctor_name'],
            'department' => $validated['department'],
            'date' => $dateTime,
            'time' => $validated['time'],
            'status' => $validated['status'] ?? 'PENDING',
            'notes' => $validated['notes'] ?? null,
        ]);

        $appointment->load('user:id,first_name,last_name');

        return response()->json([
            'id' => $appointment->id,
            'patientName' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
            'doctorName' => $appointment->doctor_name,
            'department' => $appointment->department,
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
