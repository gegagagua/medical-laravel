<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email', 'phone', 'role', 'doctor_role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::select('id', 'first_name', 'last_name', 'email', 'phone', 'role', 'doctor_role', 'created_at')
            ->findOrFail($id);

        // Get all payments where user_id matches this user's ID
        $payments = Payment::with('patient:id,first_name,last_name')
            ->where('user_id', $id)
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'invoiceNumber' => $payment->invoice_number,
                    'patientName' => $payment->patient->first_name . ' ' . $payment->patient->last_name,
                    'service' => $payment->service,
                    'doctor' => $payment->doctor,
                    'userId' => $payment->user_id,
                    'amount' => $payment->amount,
                    'date' => $payment->payment_date->toISOString(),
                    'paymentMethod' => $payment->payment_method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at->toISOString(),
                ];
            });

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'doctor_role' => $user->doctor_role,
            'created_at' => $user->created_at,
            'payments' => $payments,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:PATIENT,DOCTOR,ADMIN,LABOR',
        ];

        // Add doctor_role validation if role is LABOR
        if ($request->role === 'LABOR') {
            $rules['doctor_role'] = 'required|string|max:255';
        } else {
            $rules['doctor_role'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'doctor_role' => $request->doctor_role,
        ]);

        return response()->json([
            'user' => $user,
            'message' => 'User created successfully',
        ], 201);
    }
}
