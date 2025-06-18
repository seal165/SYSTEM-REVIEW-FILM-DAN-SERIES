<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'type',
        'facilities',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}