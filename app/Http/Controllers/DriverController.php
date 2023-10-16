<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VehicleAssigned;
use App\Models\Rent;

class DriverController extends Controller
{
    /*
        How to know that the driver has an active assignment:
        1. Check Rents that have "ongoing" or "booked" status.
        2. Check if the correct driver is assigned by looking at vehiclesAssigned and it's empID column

    */
    public function dashboard(){
        $driverID = Auth::user()->empID;
        
        /*
        $assignments = VehicleAssigned::where('empID', $driverID)
            ->whereHas('rent', function($query){
                $query->whereIn('rent_Period_Status', ['ongoing', 'Booked']);
            })
            ->with('rent')
            ->get();
        */

        $assignments = Rent::whereIn('rent_Period_Status', ['Ongoing', 'Booked'])
            ->whereHas('assignments', function($query) use ($driverID){
                $query->whereIn('empID', [$driverID]);
            })
            ->with('assignments')
            ->get();

        $activeAssignments = $assignments->filter(function ($assignment){
            return $assignment->rent_Period_Status === 'Ongoing';
        });

        $bookedAssignments = $assignments->filter(function ($assignment){
            return $assignment->rent_Period_Status === 'Booked';
        });

        return view('drivers.driver-dashboard');
    }
}
