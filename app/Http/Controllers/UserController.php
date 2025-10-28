<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'PATIENT')
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'role')
            ->get();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::select('id', 'first_name', 'last_name', 'email', 'phone', 'role')
            ->findOrFail($id);

        return response()->json($user);
    }
}
