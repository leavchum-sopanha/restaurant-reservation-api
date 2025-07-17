<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reservation::with(["customer", "table"]);

        if ($request->has("upcoming") && $request->upcoming) {
            $query->upcoming();
        }

        if ($request->has("today") && $request->today) {
            $query->today();
        }

        if ($request->has("customer_id")) {
            $query->where("customer_id", $request->customer_id);
        }

        if ($request->has("table_id")) {
            $query->where("table_id", $request->table_id);
        }

        $reservations = $query->orderBy("date_time", "asc")->get();

        return response()->json([
            "success" => true,
            "data" => $reservations,
            "message" => "Reservations retrieved successfully"
        ]);
    }

    /**
     * Store a newly created reservation.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                "customer_id" => "required|integer|exists:customers,id",
                "table_id" => "required|integer|exists:tables,id",
                "date_time" => "required|date|after:now",
                "note" => "nullable|string|max:1000",
            ]);

            $table = Table::find($validated["table_id"]);
            if (!$table->isAvailable($validated["date_time"])) {
                return response()->json([
                    "success" => false,
                    "message" => "Table is not available at the requested time",
                    "errors" => [
                        "table_id" => ["The selected table is already booked for this time."]
                    ]
                ], 422);
            }

            $reservation = Reservation::create($validated);
            $reservation->load(["customer", "table"]);

            return response()->json([
                "success" => true,
                "data" => $reservation,
                "message" => "Reservation created successfully"
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
     * Display the specified reservation.
     */
    public function show($id): JsonResponse
    {
        $reservation = Reservation::with(["customer", "table"])->find($id);

        if (!$reservation) {
            return response()->json([
                "success" => false,
                "message" => "Reservation not found!"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $reservation,
            "message" => "Reservation retrieved successfully"
        ]);
    }

    /**
     * Update the specified reservation.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                "success" => false,
                "message" => "Reservation not found!"
            ], 404);
        }

        try {
            $validated = $request->validate([
                "customer_id" => "sometimes|required|integer|exists:customers,id",
                "table_id" => "sometimes|required|integer|exists:tables,id",
                "date_time" => "sometimes|required|date|after:now",
                "note" => "nullable|string|max:1000",
            ]);

            if (isset($validated["table_id"]) && isset($validated["date_time"])) {
                $table = Table::find($validated["table_id"]);
                if (!$table->isAvailable($validated["date_time"], $reservation->id)) {
                    return response()->json([
                        "success" => false,
                        "message" => "Table is not available at the requested time",
                        "errors" => [
                            "table_id" => ["The selected table is already booked for this time."]
                        ]
                    ], 422);
                }
            }

            $reservation->update($validated);
            $reservation->load(["customer", "table"]);

            return response()->json([
                "success" => true,
                "data" => $reservation,
                "message" => "Reservation updated successfully"
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
     * Remove the specified reservation.
     */
    public function destroy($id): JsonResponse
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                "success" => false,
                "message" => "Reservation not found!"
            ], 404);
        }

        $reservation->delete();

        return response()->json([
            "success" => true,
            "message" => "Reservation deleted successfully"
        ]);
    }
}
