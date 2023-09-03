<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maintenances';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'maintID'; 
    protected $fillable = [
        'unitID', 'empID', 'mechanicAssigned', 'scheduleDate', 'notes', 'status', 'endDate'
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
     * Get the vehicle associated with the maintenance.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'unitID');
    }

    /**
     * Get the employee (assigned to maintenance) associated with the maintenance.
     */
    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'empID');
    }

    /**
     * Get the mechanic (assigned to maintenance) associated with the maintenance.
     */
    public function mechanic()
    {
        return $this->belongsTo(Employee::class, 'empID');
    }
}
