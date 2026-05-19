<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'role' => 'required|in:judge,police,lawyer,client',
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'address' => 'nullable|string',
            'bar_number' => 'nullable|string',
            'id_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Increased to 5MB
        ], [
            'phone.required' => 'The mobile number is required.',
            'phone.regex' => 'The mobile number must be exactly 10 digits and contain only numbers.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'bar_number' => $validated['bar_number'] ?? null,
            'approval_status' => 'pending',
        ];

        if ($request->hasFile('id_proof')) {
            $path = $request->file('id_proof')->store('id_proofs', 'public');
            $userData['id_proof_path'] = $path;
        }

        $user = User::create($userData);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->approval_status === 'pending') {
            return response()->json([
                'message' => 'verification pending',
            ], 403);
        }

        if ($user->approval_status === 'rejected') {
            return response()->json([
                'message' => 'verification failed',
            ], 403);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact administrator.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
