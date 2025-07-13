<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // Revoke all tokens for the authenticated user
            Auth::user()->tokens()->delete();

            // Revoke the current token
            // $request->user()->currentAccessToken()->delete();

            return JsonResponse::response(
                status: 'success',
                message: 'Logout successful',
                code: 200
            );
        } catch (Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
            ]);

            return JsonResponse::response(
                status: 'error',
                message: 'Logout failed',
                code: 500
            );
        }
    }
}