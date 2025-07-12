<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $employees = Employee::query()
                ->when($request->has('name'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                })
                ->when($request->has('division_id'), function ($query) use ($request) {
                    $query->where('division_id', $request->division_id);
                })
                ->with('division')
                ->paginate(2);

            if (empty($employees->items())) {
                return JsonResponse::response(status: 'error', message: "No employees found with the given criteria", code: 404);
            }

            return JsonResponse::response(status: 'success', message: 'Employees retrieved successfully', data: $employees->items(), pagination: $employees);
        } catch (Exception $e) {
            Log::error('Error retrieving employees: ' . $e->getMessage(), [
                'name' => $request->name,
                'division_id' => $request->division_id,
            ]);

            return JsonResponse::response(status: 'error', message: 'Failed to retrieve employees', code: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            try {
                $request->validate([
                    'phone' => 'bail|required|unique:employees,phone|string|max:16',
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                    'name' => 'required|string|max:255',
                    'division' => 'required|exists:divisions,id',
                    'position' => 'required|string|max:255',
                ]);
            } catch (ValidationException $e) {
                return JsonResponse::response(status: 'error', message: $e->getMessage(), data: $e->errors(), code: 422);
            }

            $imagePath = $request->file('image')->storePubliclyAs('employees', Str::random(10) . '.' . $request->file('image')->getClientOriginalExtension(), 'public');

            $data = [
                'image' => $imagePath,
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division,
                'position' => $request->position,
            ];

            Employee::create($data);

            return JsonResponse::response(status: 'success', message: 'Employee stored successfully', code: 200);
        } catch (Exception $e) {
            Log::error('Error storing employee: ' . $e->getMessage(), [
                'request' => $request->all(),
            ]);

            return JsonResponse::response(status: 'error', message: 'Failed to store employee', code: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return JsonResponse::response(status: 'error', message: 'Employee not found', code: 404);
            }

            try {
                $request->validate([
                    'phone' => ['bail', 'required', Rule::unique('employees', 'phone')->ignore($employee->id), 'string', 'max:16'],
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'name' => 'required|string|max:255',
                    'division' => 'required|exists:divisions,id',
                    'position' => 'required|string|max:255',
                ]);
            } catch (ValidationException $e) {
                return JsonResponse::response(status: 'error', message: $e->getMessage(), data: $e->errors(), code: 422);
            }

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->storePubliclyAs('employees', Str::random(10) . '.' . $request->file('image')->getClientOriginalExtension(), 'public');

                if (Storage::disk('public')->exists($employee->image) && $employee->image !== 'employees/default.jpg') {
                    Storage::disk('public')->delete($employee->image);
                }
            }

            $data = [
                'image' => $request->hasFile('image') ? $imagePath : $employee->image,
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division,
                'position' => $request->position,
            ];

            $employee->update($data);

            return JsonResponse::response(status: 'success', message: 'Employee updated successfully', code: 200);
        } catch (Exception $e) {
            Log::error('Error storing employee: ' . $e->getMessage(), [
                'request' => $request->all(),
            ]);

            return JsonResponse::response(status: 'error', message: 'Failed to update employee', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return JsonResponse::response(status: 'error', message: 'Employee not found', code: 404);
            }

            if (Storage::disk('public')->exists($employee->image) && $employee->image !== 'employees/default.jpg') {
                Storage::disk('public')->delete($employee->image);
            }

            $employee->delete();

            return JsonResponse::response(status: 'success', message: 'Employee deleted successfully', code: 200);
        } catch (Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage(), [
                'id' => $id,
            ]);

            return JsonResponse::response(status: 'error', message: 'Failed to delete employee', code: 500);
        }
    }
}
