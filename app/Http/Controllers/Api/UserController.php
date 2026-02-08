<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        return response()->json($query->get());
    }

    public function show(User $user) {
    return response()->json($user->loadCount('jobs')); // Returns user with total jobs done
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:super_admin,employee'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    public function destroy(User $user)
{
    // Use auth('sanctum')->id() to be explicit since you're using Sanctum
    $currentUserId = auth('sanctum')->id();

    // Prevent self-deletion by comparing the IDs directly
    if ($currentUserId == $user->id) {
        return response()->json([
            'message' => 'Security Breach: You cannot delete your own administrative account.'
        ], 403);
    }

    $user->delete();
    return response()->json(['message' => 'User removed from system.']);
}
}