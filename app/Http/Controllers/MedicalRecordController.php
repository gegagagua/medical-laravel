<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::with('user:id,first_name,last_name')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($records);
    }

    public function show($id)
    {
        $record = MedicalRecord::with('user')->findOrFail($id);
        
        return response()->json($record);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $record = MedicalRecord::create($validated);

        return response()->json($record, 201);
    }
}
