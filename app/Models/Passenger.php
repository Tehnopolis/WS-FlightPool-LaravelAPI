<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Passenger extends Model
{
    protected $table = 'passengers';
    
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'birth_date',
        'document_number',
        'place_from',
        'place_back',
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
}