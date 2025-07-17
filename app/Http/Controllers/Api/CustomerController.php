<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(): JsonResponse
    {
        $customers = Customer::with("reservations")->get();

        return response()->json([
            "success" => true,
            "data" => $customers,
            "message" => "Customers retrieved successfully"
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                "name" => "required|string|max:255",
                "phone" => "required|string|unique:customers,phone|max:20",
                "email" => "required|email|unique:customers,email|max:255",
            ]);

            $customer = Customer::create($validated);

            return response()->json([
                "success" => true,
                "data" => $customer,
                "message" => "Customer created successfully"
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show($id): JsonResponse
    {
        $customer = Customer::with("reservations.table")->find($id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found!"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $customer,
            "message" => "Customer retrieved successfully"
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found!"
            ], 404);
        }

        try {
            $validated = $request->validate([
                "name" => "sometimes|required|string|max:255",
                "phone" => "sometimes|required|string|max:20|unique:customers,phone," . $customer->id,
                "email" => "sometimes|required|email|max:255|unique:customers,email," . $customer->id,
            ]);

            $customer->update($validated);

            return response()->json([
                "success" => true,
                "data" => $customer,
                "message" => "Customer updated successfully"
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified customer.
     */
    public function destroy($id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found!"
            ], 404);
        }

        $customer->delete();

        return response()->json([
            "success" => true,
            "message" => "Customer deleted successfully"
        ]);
    }
}
