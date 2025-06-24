<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'destination',
        'price',
        'start_date',
        'end_date',
        'available_slots',
        'is_visible',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the bookings for the travel package.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if the package has any non-completed bookings.
     *
     * @return bool
     */
    public function hasActiveBookings()
    {
        return $this->bookings()->whereNotIn('status', ['completed', 'cancelled'])->exists();
    }

    /**
     * Scope a query to only include visible travel packages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
