<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('patient:id,first_name,last_name,id_number,date_of_birth')
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'invoiceNumber' => $payment->invoice_number,
                    'patientId' => $payment->patient_id,
                    'appointmentId' => $payment->appointment_id,
                    'patientName' => $payment->patient->first_name . ' ' . $payment->patient->last_name,
                    'patientIdNumber' => $payment->patient->id_number ?? null,
                    'patientDateOfBirth' => $payment->patient->date_of_birth?->toISOString(),
                    'service' => $payment->service,
                    'doctor' => $payment->doctor,
                    'userId' => $payment->user_id,
                    'amount' => $payment->amount,
                    'hasDiscount' => $payment->has_discount ?? false,
                    'discountPercentage' => $payment->discount_percentage,
                    'servicesDiscounts' => $payment->services_discounts,
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
            'user_id' => 'nullable|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'service' => 'required|string|max:255',
            'doctor' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,transfer',
            'status' => 'sometimes|in:paid,pending,cancelled',
            'services' => 'nullable|array',
            'services.*.name' => 'nullable|string',
            'services.*.price' => 'nullable|numeric',
            'services.*.discount' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calculate discount information
        $hasDiscount = false;
        $servicesDiscounts = null;
        
        if (isset($validated['services']) && is_array($validated['services'])) {
            $servicesDiscounts = [];
            foreach ($validated['services'] as $service) {
                $discount = isset($service['discount']) ? floatval($service['discount']) : 0;
                if ($discount > 0) {
                    $hasDiscount = true;
                }
                $servicesDiscounts[] = [
                    'name' => $service['name'] ?? '',
                    'price' => $service['price'] ?? 0,
                    'discount' => $discount
                ];
            }
        }

        // Calculate average discount percentage if there are discounts
        $discountPercentage = null;
        if ($hasDiscount && $servicesDiscounts) {
            $totalOriginal = 0;
            $totalDiscounted = 0;
            foreach ($servicesDiscounts as $service) {
                $originalPrice = floatval($service['price'] ?? 0);
                $discount = floatval($service['discount'] ?? 0);
                $totalOriginal += $originalPrice;
                $totalDiscounted += $originalPrice * (1 - $discount / 100);
            }
            if ($totalOriginal > 0) {
                $discountPercentage = (($totalOriginal - $totalDiscounted) / $totalOriginal) * 100;
            }
        }

        $payment = Payment::create([
            'patient_id' => $validated['patient_id'],
            'user_id' => $validated['user_id'] ?? null,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'service' => $validated['service'],
            'doctor' => $validated['doctor'] ?? null,
            'amount' => $validated['amount'],
            'has_discount' => $hasDiscount,
            'discount_percentage' => $discountPercentage,
            'services_discounts' => $servicesDiscounts,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        $payment->load('patient:id,first_name,last_name,id_number,date_of_birth');

        return response()->json([
            'id' => $payment->id,
            'invoiceNumber' => $payment->invoice_number,
            'patientId' => $payment->patient_id,
            'appointmentId' => $payment->appointment_id,
            'patientName' => $payment->patient->first_name . ' ' . $payment->patient->last_name,
            'patientIdNumber' => $payment->patient->id_number ?? null,
            'patientDateOfBirth' => $payment->patient->date_of_birth?->toISOString(),
            'service' => $payment->service,
            'doctor' => $payment->doctor,
            'amount' => $payment->amount,
            'hasDiscount' => $payment->has_discount,
            'discountPercentage' => $payment->discount_percentage,
            'servicesDiscounts' => $payment->services_discounts,
            'date' => $payment->payment_date->toISOString(),
            'paymentMethod' => $payment->payment_method,
            'status' => $payment->status,
            'created_at' => $payment->created_at->toISOString(),
            'message' => 'Payment created successfully',
        ], 201);
    }

    public function byDoctor(Request $request)
    {
        $user = $request->user();
        $doctorName = $user->first_name . ' ' . $user->last_name;

        $payments = Payment::with('patient:id,first_name,last_name,id_number,date_of_birth')
            ->where('doctor', $doctorName)
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'invoiceNumber' => $payment->invoice_number,
                    'patientName' => $payment->patient->first_name . ' ' . $payment->patient->last_name,
                    'patientIdNumber' => $payment->patient->id_number ?? null,
                    'patientDateOfBirth' => $payment->patient->date_of_birth?->toISOString(),
                    'service' => $payment->service,
                    'doctor' => $payment->doctor,
                    'amount' => $payment->amount,
                    'hasDiscount' => $payment->has_discount ?? false,
                    'discountPercentage' => $payment->discount_percentage,
                    'servicesDiscounts' => $payment->services_discounts,
                    'date' => $payment->payment_date->toISOString(),
                    'paymentMethod' => $payment->payment_method,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at->toISOString(),
                ];
            });

        return response()->json($payments);
    }
}
