<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAssigned extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'vehicles_assigned';
    protected $primaryKey = 'assignedID'; 
    protected $fillable = [
        'unitID', 'rentID', 'empID', 'reserveID',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class, 'rentID');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'reserveID');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'unitID');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empID');
    }
    

    
}
