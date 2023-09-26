<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'vehicle_types';
    protected $primaryKey = 'vehicle_Type_ID'; 
    protected $fillable = [
        'vehicle_Type', 'pic', 'description'
    ];

    /**
     * Define the one-to-many relationship with Vehicle.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_Type_ID');
    }

    /**
     * Define a one-to-many relationship with VehicleTypesBooked.
     */
    public function vehicleTypesBooked()
    {
        return $this->hasMany(VehicleTypeBooked::class, 'vehicle_Type_ID');
    }
}
