<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderByDesc('created_at')->get()->map(function (Patient $p) {
            return [
                'id' => $p->id,
                'gegasCode' => $p->gegas_code,
                'fullName' => $p->first_name.' '.$p->last_name,
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
            'gegas_code' => $p->gegas_code,
            'date_of_birth' => $p->date_of_birth?->format('Y-m-d'),
            'age' => $p->age,
            'gender' => $p->gender,
            'phone' => $p->phone,
            'email' => $p->email,
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
            'gegas_code' => 'nullable|string|max:32',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|string|email|max:255',
            'diagnosis' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $requestedCode = isset($validated['gegas_code']) ? trim((string) $validated['gegas_code']) : '';
        if ($requestedCode !== '') {
            $existingPatient = Patient::query()->where('gegas_code', $requestedCode)->first();
            if ($existingPatient) {
                return $this->gegasCodeTakenResponse($requestedCode);
            }
        }

        try {
            $patient = DB::transaction(function () use ($validated) {
                Patient::query()->orderByDesc('id')->limit(1)->lockForUpdate()->first();

                $dateOfBirth = \Carbon\Carbon::parse($validated['date_of_birth']);
                $validated['age'] = $dateOfBirth->age;
                $validated['gegas_code'] = ! empty($validated['gegas_code'])
                    ? trim((string) $validated['gegas_code'])
                    : Patient::nextNumericGegasCode();

                return Patient::create($validated);
            });
        } catch (QueryException $exception) {
            if ($this->isGegasCodeUniqueViolation($exception)) {
                return $this->gegasCodeTakenResponse($requestedCode);
            }
            throw $exception;
        }

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
            'id_number' => 'required|string|max:20|unique:patients,id_number,'.$id,
            'gegas_code' => 'nullable|string|max:32',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|string|email|max:255',
            'diagnosis' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $requestedCode = isset($validated['gegas_code']) ? trim((string) $validated['gegas_code']) : '';
        if ($requestedCode !== '') {
            $existingPatient = Patient::query()
                ->where('gegas_code', $requestedCode)
                ->where('id', '!=', $id)
                ->first();
            if ($existingPatient) {
                return $this->gegasCodeTakenResponse($requestedCode, (int) $id);
            }
        }

        // Calculate age from date of birth
        $dateOfBirth = \Carbon\Carbon::parse($validated['date_of_birth']);
        $validated['age'] = $dateOfBirth->age;

        try {
            $patient->update($validated);
        } catch (QueryException $exception) {
            if ($this->isGegasCodeUniqueViolation($exception)) {
                return $this->gegasCodeTakenResponse($requestedCode, (int) $id);
            }
            throw $exception;
        }

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

    private function isGegasCodeUniqueViolation(QueryException $exception): bool
    {
        $message = strtolower((string) $exception->getMessage());
        $driverCode = (int) ($exception->errorInfo[1] ?? 0);

        if ($driverCode === 1062 && str_contains($message, 'gegas_code')) {
            return true;
        }

        return str_contains($message, 'patients_gegas_code_unique');
    }

    private function gegasCodeTakenResponse(string $code, ?int $excludePatientId = null)
    {
        $query = Patient::query()->where('gegas_code', $code);
        if ($excludePatientId !== null) {
            $query->where('id', '!=', $excludePatientId);
        }
        $existingPatient = $query->first();

        if ($existingPatient) {
            return response()->json([
                'message' => 'კოდი უკვე გამოყენებულია სხვა პაციენტზე ('.$existingPatient->first_name.' '.$existingPatient->last_name.')',
            ], 422);
        }

        return response()->json([
            'message' => 'კოდი უკვე გამოყენებულია სხვა პაციენტზე',
        ], 422);
    }
}
