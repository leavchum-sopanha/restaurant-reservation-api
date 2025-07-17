<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'table_id',
        'date_time',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_time' => 'datetime',
    ];

    /**
     * Get the customer that owns the reservation.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the table that owns the reservation.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Scope a query to only include upcoming reservations.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_time', '>=', now());
    }

    /**
     * Scope a query to only include reservations for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date_time', today());
    }
}

