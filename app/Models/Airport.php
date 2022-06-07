<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Airport extends Model
{
    protected $table = 'airports';
    
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'city',
        'name',
        'iata'
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
        'updated_at' => 'datetime'
    ];

    public function flightsFrom() {
        return $this->belongsTo(\App\Models\Flight::class, 'from_id');
    }
    public function flightsTo() {
        return $this->belongsTo(\App\Models\Flight::class, 'to_id');
    }
}
