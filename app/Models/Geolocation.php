<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    use HasFactory;
    
    public function driver(){
        return $this->belongsTo(Employee::class);
    }
    
    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
