<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Flight extends Model
{
    protected $table = 'flights';
    
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flight_code',
        'from_id',
        'to_id',
        'time_from',
        'time_to',
        'cost'
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
        return $this->hasOne(\App\Models\Airport::class, 'id', 'from_id');
    }
    public function to() {
        return $this->hasOne(\App\Models\Airport::class, 'id', 'to_id');
    }
}