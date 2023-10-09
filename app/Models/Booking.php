<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'booking';

    protected $primaryKey = 'reserveID';
    
     protected $fillable = [
        'cust_first_name', 'cust_last_name', 'cust_email', 'tariffID', 'startDate', 'endDate', 'mobileNum', 'pickUp_Address',
        'note', 'downpayment_Fee', 'gcash_RefNum', 'subtotal', 'status'
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'custID');
    }

    /**
     * Get the vehicle associated with the reservation.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'unitID');
    }

    /**
     * Get the tariff associated with the reservation.
     */
    public function tariff()
    {
        return $this->belongsTo(Tariff::class, 'tariffID');
    }

    /**
     * Get the rental associated with the reservation.
     */
    public function rental()
    {
        return $this->hasOne(Rent::class, 'rentID');
    }

    /**
     * Define a one-to-many relationship with VehicleTypeBooked.
     */
    public function vehicleTypesBooked()
    {
        return $this->hasMany(VehicleTypeBooked::class, 'reserveID');
    }

    
}
