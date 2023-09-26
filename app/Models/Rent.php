<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'rents';
    protected $primaryKey = 'rentID';
     protected $fillable = [
        'reserveID', 'driverID', 'rent_Period_Status', 'extra_Hours',
        'payment_Status', 'total_Price', 'balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Get the reservation associated with the rental.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'reserveID');
    }

    /*
     Get the driver (associated with the rental) information.
    */
    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driverID');
    }
    
    public function assignments()
    {
        return $this->hasMany(VehicleAssigned::class, 'rentID');
    }
    public function vehicles()
    {
        return $this->hasManyThrough(Vehicle::class, VehicleAssigned::class, 'rentID', 'rentID', 'assignedID', 'unitID');
    }
/*
    public function employees()
    {
        return $this->hasManyThrough(Employee::class, VehicleAssigned::class, 'rentID', 'rentID', 'assignedID', 'empID');
    }
    */

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'vehicles_assigned', 'rentID', 'empID');
    }
}
