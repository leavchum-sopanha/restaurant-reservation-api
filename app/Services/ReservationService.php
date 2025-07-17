
<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    /**
     * Get available tables for a specific date and time.
     *
     * @param string $dateTime
     * @param int|null $capacity
     * @return Collection
     */
    public function getAvailableTables(string $dateTime, ?int $capacity = null): Collection
    {
        $query = Table::query();

        if ($capacity) {
            $query->where("capacity", ">=", $capacity);
        }

        $tables = $query->get();

        return $tables->filter(function ($table) use ($dateTime) {
            return $table->isAvailable($dateTime);
        });
    }

    /**
     * Check for conflicting reservations.
     *
     * @param int $tableId
     * @param string $dateTime
     * @param int|null $excludeReservationId
     * @return bool
     */
    public function hasConflictingReservation(int $tableId, string $dateTime, ?int $excludeReservationId = null): bool
    {
        $query = Reservation::where("table_id", $tableId)
            ->where("date_time", $dateTime);

        if ($excludeReservationId) {
            $query->where("id", "!=", $excludeReservationId);
        }

        return $query->exists();
    }

    /**
     * Get reservations for a specific date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getReservationsByDateRange(string $startDate, string $endDate): Collection
    {
        return Reservation::with(["customer", "table"])
            ->whereBetween("date_time", [$startDate, $endDate])
            ->orderBy("date_time")
            ->get();
    }

    /**
     * Get today's reservations.
     *
     * @return Collection
     */
    public function getTodaysReservations(): Collection
    {
        return Reservation::with(["customer", "table"])
            ->whereDate("date_time", today())
            ->orderBy("date_time")
            ->get();
    }

    /**
     * Get upcoming reservations.
     *
     * @param int $days
     * @return Collection
     */
    public function getUpcomingReservations(int $days = 7): Collection
    {
        $endDate = now()->addDays($days);

        return Reservation::with(["customer", "table"])
            ->whereBetween("date_time", [now(), $endDate])
            ->orderBy("date_time")
            ->get();
    }

    /**
     * Get customer's reservation history.
     *
     * @param int $customerId
     * @param bool $upcomingOnly
     * @return Collection
     */
    public function getCustomerReservations(int $customerId, bool $upcomingOnly = false): Collection
    {
        $query = Reservation::with(["table"])
            ->where("customer_id", $customerId);

        if ($upcomingOnly) {
            $query->where("date_time", ">=", now());
        }

        return $query->orderBy("date_time", "desc")->get();
    }

    /**
     * Get table's reservation schedule.
     *
     * @param int $tableId
     * @param string $date
     * @return Collection
     */
    public function getTableSchedule(int $tableId, string $date): Collection
    {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        return Reservation::with(["customer"])
            ->where("table_id", $tableId)
            ->whereBetween("date_time", [$startOfDay, $endOfDay])
            ->orderBy("date_time")
            ->get();
    }

    /**
     * Validate reservation time constraints.
     *
     * @param string $dateTime
     * @return array
     */
    public function validateReservationTime(string $dateTime): array
    {
        $reservationTime = Carbon::parse($dateTime);
        $now = now();
        $errors = [];

        // Check if reservation is in the past
        if ($reservationTime->isPast()) {
            $errors[] = "Reservation cannot be made for past date and time.";
        }

        // Check if reservation is too far in advance (e.g., 3 months)
        if ($reservationTime->isAfter($now->copy()->addMonths(3))) {
            $errors[] = "Reservation cannot be made more than 3 months in advance.";
        }

        // Check if reservation is during business hours (example: 10 AM to 10 PM)
        $hour = $reservationTime->hour;
        if ($hour < 10 || $hour >= 22) {
            $errors[] = "Reservations are only available between 10:00 AM and 10:00 PM.";
        }

        return $errors;
    }

    /**
     * Get reservation statistics.
     *
     * @return array
     */
    public function getReservationStats(): array
    {
        $today = today();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            "today" => Reservation::whereDate("date_time", $today)->count(),
            "this_week" => Reservation::where("date_time", ">=", $thisWeek)->count(),
            "this_month" => Reservation::where("date_time", ">=", $thisMonth)->count(),
            "upcoming" => Reservation::where("date_time", ">=", now())->count(),
            "total" => Reservation::count(),
        ];
    }
}

