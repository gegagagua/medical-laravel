<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $bookings = $query->orderBy('date', 'asc')->orderBy('time', 'asc')->get();

        return response()->json($bookings->map(fn (Booking $b) => $this->serialize($b)));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'doctor_name' => 'nullable|string|max:255',
            'patient_name' => 'required|string|max:255',
            'patient_id_number' => 'required|string|max:50',
            'patient_phone' => 'required|string|max:50',
            'date' => 'required|date',
            'time' => 'required|string|max:10',
        ]);

        $doctorName = $validated['doctor_name'] ?? null;
        if (! $doctorName) {
            $doctor = \App\Models\User::find($validated['doctor_id']);
            $doctorName = $doctor ? trim($doctor->first_name.' '.$doctor->last_name) : '';
        }

        $booking = Booking::create([
            'doctor_id' => $validated['doctor_id'],
            'doctor_name' => $doctorName,
            'patient_name' => trim($validated['patient_name']),
            'patient_id_number' => trim($validated['patient_id_number']),
            'patient_phone' => trim($validated['patient_phone']),
            'date' => $validated['date'],
            'time' => $validated['time'],
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $this->serialize($booking),
        ], 201);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }

    private function serialize(Booking $booking): array
    {
        return [
            'id' => $booking->id,
            'doctor_id' => $booking->doctor_id,
            'doctorName' => $booking->doctor_name,
            'patientName' => $booking->patient_name,
            'patientIdNumber' => $booking->patient_id_number,
            'patientPhone' => $booking->patient_phone,
            'date' => $booking->date?->format('Y-m-d'),
            'time' => $booking->time,
            'createdAt' => $booking->created_at?->toISOString(),
        ];
    }
}
