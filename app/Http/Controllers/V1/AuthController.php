<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                ...$request->except('password_confirmation')
            ]);

            $token = $user->createToken('expenseTracker')->plainTextToken;

            return response()->json([
                'userDetails' => $user,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }

    }

    public function login(LoginRequest $request): JsonResponse
    {
        try{
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid username or password provided.',
                ], 404);
            }

            $user = User::firstWhere(['email' => $request->email]);

            $token = $user->createToken('expenseTracker')->plainTextToken;

            return response()->json([
                'userDetails' => $user,
                'token' => $token
            ]);

        } catch(Exception $e) {
            report($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
