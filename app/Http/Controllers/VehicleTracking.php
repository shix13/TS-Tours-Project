<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleAssigned;
use App\Models\Rent;

class VehicleTracking extends Controller
{
    //
    public function vehicleIndex(){
        $activeTasks = Rent::whereIn('rent_Period_Status', ['Ongoing'])
        ->with(['assignments'])
        ->get();

        foreach($activeTasks as $activeTask){
            $assignments = $activeTask->assignments;
        }

        return view('employees.vehicleTracking', compact('activeTask', 'assignments'));
    }
}
