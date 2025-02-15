<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'code' => JsonResponse::HTTP_UNAUTHORIZED,
                'message' => 'The provided credentials are incorrect.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'token' => $user->createToken('API Token')->plainTextToken,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
    
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'message' => 'User logged out successfully.',
        ]);
    }
}
