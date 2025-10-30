<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('patient:id,first_name,last_name')
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'invoiceNumber' => $payment->invoice_number,
                    'patientName' => $payment->patient->first_name . ' ' . $payment->patient->last_name,
                    'service' => $payment->service,
                    'amount' => $payment->amount,
                    'date' => $payment->payment_date->toISOString(),
                    'paymentMethod' => $payment->payment_method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at->toISOString(),
                ];
            });

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,transfer',
            'status' => 'sometimes|in:paid,pending,cancelled',
        ]);

        $payment = Payment::create([
            'patient_id' => $validated['patient_id'],
            'service' => $validated['service'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        $payment->load('patient:id,first_name,last_name');

        return response()->json([
            'id' => $payment->id,
            'invoiceNumber' => $payment->invoice_number,
            'patientName' => $payment->patient->first_name . ' ' . $payment->patient->last_name,
            'service' => $payment->service,
            'amount' => $payment->amount,
            'date' => $payment->payment_date->toISOString(),
            'paymentMethod' => $payment->payment_method,
            'status' => $payment->status,
            'created_at' => $payment->created_at->toISOString(),
            'message' => 'Payment created successfully',
        ], 201);
    }
}
