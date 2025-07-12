<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Division;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DivisionController extends Controller
{
    public function getAllDivisions(Request $request)
    {
        try {
            $divisions = Division::query()
                ->when($request->has('name'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                })
                ->paginate(2);

            if (empty($divisions->items())) {
                return JsonResponse::response(status: 'error', message: "No division with name $request->name found", code: 404);
            }

            return JsonResponse::response(status: 'success', message: 'Divisions retrieved successfully', data: $divisions->items(2), pagination: $divisions);
        } catch (Exception $e) {
            Log::error('Error retrieving divisions: ' . $e->getMessage(), [
                'name' => $request->name,
            ]);

            return JsonResponse::response(status: 'error', message: 'Failed to retrieve divisions', code: 500);
        }
    }
}
