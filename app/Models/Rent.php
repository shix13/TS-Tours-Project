<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Rent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reserveID');
    }

    /**
     * Get the driver (associated with the rental) information.
     */
    public function driver()
    {
        return $this->belongsTo(Employee::class, 'empID');
    }
}
