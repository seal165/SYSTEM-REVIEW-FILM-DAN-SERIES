<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genre',
        'duration',
        'release_date',
        'rating',
        'description',
        'poster_url',
        'price',
        'status'
    ];

    protected $casts = [
        'release_date' => 'date',
        'price' => 'decimal:2'
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function getDurationInHoursAttribute()
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        return $hours . 'h ' . $minutes . 'm';
    }
}