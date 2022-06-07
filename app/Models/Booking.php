<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Booking extends Model
{
    protected $table = 'bookings';
    
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flight_from',
        'flight_back',
        'date_from',
        'date_back',
        'code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function from() {
        return $this->hasOne(\App\Models\Flight::class, 'id', 'flight_from')
            ->with('from')
            ->with('to');
    }
    public function back() {
        return $this->hasOne(\App\Models\Flight::class, 'id', 'flight_back')
            ->with('from')
            ->with('to');
    }
    public function passengers() {
        return $this->hasMany(\App\Models\Passenger::class, 'booking_id', 'id');
    }
}