<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ১. ইউজার রেজিস্ট্রেশন
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, // ডিফল্টভাবে 'User' বা 'Customer' রোল আইডি (আপনার ডাটাবেস অনুযায়ী)
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->load('role') // এখানে এখন রোল অবজেক্টসহ আসবে
        ], 201);
    }

    // ২. ইউজার লগইন
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request->email)->with('role')->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user // with('role') করার কারণে এখানে সব তথ্য আছে
        ]);
    }
}