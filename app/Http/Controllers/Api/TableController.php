<?php

namespace App\Http\Controllers\Api;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class TableController extends Controller
{
    /**
     * Display a listing of tables.
     */
    public function index(): JsonResponse
    {
        $tables = Table::with("reservations")->get();

        return response()->json([
            "success" => true,
            "data" => $tables,
            "message" => "Tables retrieved successfully"
        ]);
    }

    /**
     * Store a newly created table.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                "number" => "required|integer|unique:tables,number|min:1",
                "capacity" => "required|integer|min:1|max:20",
            ]);

            $table = Table::create($validated);

            return response()->json([
                "success" => true,
                "data" => $table,
                "message" => "Table created successfully"
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
     * Display the specified table.
     */
    public function show($id): JsonResponse
    {
        $table = Table::with('reservations.customer')->find($id);

        if ($table) {
            return response()->json([
                "success" => true,
                "data" => $table,
                "message" => "Table retrieved successfully"
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Table not found!"
            ], 404);
        }
    }

    /**
     * Update the specified table.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json([
                "success" => false,
                "message" => "Table not found!"
            ], 404);
        }

        try {
            $validated = $request->validate([
                "number" => "sometimes|required|integer|min:1|unique:tables,number," . $table->id,
                "capacity" => "sometimes|required|integer|min:1|max:20",
            ]);

            $table->update($validated);

            return response()->json([
                "success" => true,
                "data" => $table,
                "message" => "Table updated successfully"
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
     * Remove the specified table.
     */
    public function destroy($id): JsonResponse
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json([
                "success" => false,
                "message" => "Table not found!"
            ], 404);
        }

        $table->delete();

        return response()->json([
            "success" => true,
            "message" => "Table deleted successfully"
        ]);
    }

    /**
     * Check table availability for a specific date and time.
     */
    public function checkAvailability(Request $request, Table $table): JsonResponse
    {
        try {
            $validated = $request->validate([
                "date_time" => "required|date|after:now",
                "exclude_reservation_id" => "sometimes|integer|exists:reservations,id",
            ]);

            $isAvailable = $table->isAvailable(
                $validated["date_time"],
                $validated["exclude_reservation_id"] ?? null
            );

            return response()->json([
                "success" => true,
                "data" => [
                    "table_id" => $table->id,
                    "table_number" => $table->number,
                    "date_time" => $validated["date_time"],
                    "is_available" => $isAvailable
                ],
                "message" => $isAvailable ? "Table is available" : "Table is not available"
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
        }
    }
}
