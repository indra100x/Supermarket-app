<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::where('role', 'cashier')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $cashier = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cashier'
        ]);

        return response()->json(['message' => 'Cashier added successfully', 'cashier' => $cashier]);
    }

    public function show(User $user)
    {
        if ($user->role !== 'cashier') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'cashier') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'string|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6'
        ]);

        if ($request->has('password')) {
            $request['password'] = Hash::make($request->password);
        }

        $user->update($request->only(['name', 'email', 'password']));

        return response()->json(['message' => 'Cashier updated successfully', 'cashier' => $user]);
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'cashier') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'Cashier deleted successfully']);
    }
}
