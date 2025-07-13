<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return JsonResponse::response(status: 'success', message: 'Login successful', data: [
                    'token' => $token,
                    'admin' => $user,
                ]);
            } else {
                return JsonResponse::response(status: 'error', message: 'Invalid credentials', code: 401);
            }
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'username' => $request->username,
                'ip' => $request->ip(),
            ]);

            return JsonResponse::response(status: 'error', message: 'An error occurred during login', code: 500);
        }
    }
}