<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): JsonResponse
    {
        // 1. Validate credentials with standard Laravel validation
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt authentication
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // 3. Retrieve the authenticated user instance
        $user = Auth::user();

        // 4. Revoke previous tokens if you want to allow only one active session (Optional)
        // $user->tokens()->delete();

        // 5. Generate a new plain text token
        $token = $request->user()->createToken('api-token')->plainTextToken;

        // 6. Return professional response
        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ], 200);
    }

    /**
     * Log the user out (Revoke the token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}