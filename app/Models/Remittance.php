<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remittance extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'remittances';
    protected $primaryKey = 'remitID';

    protected $fillable = [
        'clerkID',
        'driverID',
        'rentID',
        'receiptNum',
        'amount',
        'paymentType', 
    ];

    // Define relationships with other models here if needed
    public function clerk()
    {
        return $this->belongsTo(Employee::class, 'clerkID', 'empID');
    }

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driverID', 'empID');
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class, 'rentID');
    }
}
