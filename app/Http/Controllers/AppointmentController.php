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
                'patientName' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
                'doctorName' => $appointment->doctor_name,
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
            'patientName' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
            'doctorName' => $appointment->doctor_name,
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
            'doctor_name' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json($appointment, 201);
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
