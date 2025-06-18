<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'theater_id',
        'show_date',
        'show_time',
        'ticket_price',
        'available_seats',
    ];

    protected $casts = [
        'show_date' => 'date',
        'show_time' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getBookedSeatsAttribute()
    {
        return $this->bookings()->where('status', 'confirmed')->sum('seats_booked');
    }

    public function getAvailableSeatsAttribute()
    {
        return $this->theater->capacity - $this->booked_seats;
    }
}