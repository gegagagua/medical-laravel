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
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:30',
            'diagnosis' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $patient = Patient::create($validated);

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ], 201);
    }
}
