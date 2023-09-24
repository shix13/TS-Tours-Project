<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTypeBooked extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'vehicle_types_booked';
    protected $primaryKey = 'vehicle_Booked_ID'; 
    protected $fillable = [
        'vehicle_Type_ID', 'reserveID', 'quantity',
    ];

    /**
     * Define a belongs-to relationship with Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'reserveID');
    }

    /**
     * Define a one-to-one relationship with VehicleType.
     */
    public function vehicleType()
    {
        return $this->hasOne(VehicleType::class, 'vehicle_Type_ID');
    }
}
