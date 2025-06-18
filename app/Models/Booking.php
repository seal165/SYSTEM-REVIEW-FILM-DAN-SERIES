<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'showtime_id',
        'booking_reference',
        'seats_booked',
        'total_amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_method',
        'status',
        'notes',
        'booking_date',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'confirmed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'completed' => 'bg-primary',
            default => 'bg-secondary'
        };
    }
}