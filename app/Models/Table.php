<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'capacity',
    ];

    /**
     * Get the reservations for the table.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Check if table is available at a specific date and time.
     *
     * @param string $dateTime
     * @param int|null $excludeReservationId
     * @return bool
     */
    public function isAvailable(string $dateTime, ?int $excludeReservationId = null): bool
    {
        $query = $this->reservations()
            ->where('date_time', $dateTime);

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->count() === 0;
    }
}

