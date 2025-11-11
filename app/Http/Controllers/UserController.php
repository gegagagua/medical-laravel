<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return response()->json($user);
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
