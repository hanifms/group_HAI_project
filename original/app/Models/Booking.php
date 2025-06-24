<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    /**
     * Status constants for bookings
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'travel_package_id',
        'booking_date',
        'status',
        'number_of_travelers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'datetime',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the travel package that belongs to the booking.
     */
    public function travelPackage()
    {
        return $this->belongsTo(TravelPackage::class);
    }

    /**
     * Get the review for the booking.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if the booking is eligible for review.
     *
     * @return bool
     */
    public function isEligibleForReview()
    {
        return $this->status === self::STATUS_COMPLETED && !$this->review;
    }
}
