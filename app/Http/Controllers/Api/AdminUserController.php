<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Users\CreateAdminAction;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminUserController extends Controller
{
     
    use AuthorizesRequests;
   public function store(Request $request, CreateAdminAction $action)
{
    // TEMP: allow creation if no users exist
    $this->authorize('createAdmin', User::class);


    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]);

    $admin = $action->execute($data);

    return response()->json([
        'message' => 'Admin created',
        'admin'   => $admin,
    ], 201);
}

}
