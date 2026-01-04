<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderByDesc('created_at')->get()->map(function (Patient $p) {
            return [
                'id' => $p->id,
                'fullName' => $p->first_name . ' ' . $p->last_name,
                'idNumber' => $p->id_number,
                'dateOfBirth' => $p->date_of_birth?->toISOString(),
                'age' => $p->age,
                'gender' => $p->gender,
                'phone' => $p->phone,
                'diagnosis' => $p->diagnosis,
                'status' => $p->status,
                'lastVisit' => optional($p->last_visit_at)->toISOString(),
            ];
        });

        return response()->json($patients);
    }

    public function show($id)
    {
        $p = Patient::findOrFail($id);
        return response()->json([
            'id' => $p->id,
            'first_name' => $p->first_name,
            'last_name' => $p->last_name,
            'id_number' => $p->id_number,
            'date_of_birth' => $p->date_of_birth?->toISOString(),
            'age' => $p->age,
            'gender' => $p->gender,
            'phone' => $p->phone,
            'diagnosis' => $p->diagnosis,
            'status' => $p->status,
            'last_visit_at' => $p->last_visit_at?->toISOString(),
            'created_at' => $p->created_at->toISOString(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:20|unique:patients,id_number',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:30',
            'diagnosis' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Calculate age from date of birth
        $dateOfBirth = \Carbon\Carbon::parse($validated['date_of_birth']);
        $validated['age'] = $dateOfBirth->age;

        $patient = Patient::create($validated);

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:20|unique:patients,id_number,' . $id,
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:30',
            'diagnosis' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // Calculate age from date of birth
        $dateOfBirth = \Carbon\Carbon::parse($validated['date_of_birth']);
        $validated['age'] = $dateOfBirth->age;

        $patient->update($validated);

        return response()->json([
            'message' => 'Patient updated successfully',
            'patient' => $patient,
        ]);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully',
        ]);
    }
}
