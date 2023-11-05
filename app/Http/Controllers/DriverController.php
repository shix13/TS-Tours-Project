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
    public function showActive(){
        $driverID = Auth::user()->empID;
        
        /*
        $assignments = VehicleAssigned::where('empID', $driverID)
            ->whereHas('rent', function($query){
                $query->whereIn('rent_Period_Status', ['ongoing', 'Booked']);
            })
            ->with('rent')
            ->get();
        */

        /*
            Codeblock gets active tasks where rent period status is ongoing and the assignment's empid is equals
            to the current logged in user's ID.
        */
        $activeTask = Rent::whereIn('rent_Period_Status', ['Ongoing'])
            ->whereHas('assignments', function($query) use ($driverID){
                $query->whereIn('empID', [$driverID]);
            })
            ->with(['assignments' => function ($query) use ($driverID) {
                $query->where('empID', $driverID)
                ->with('vehicle');
            }])
            ->first();

        return view('drivers.activeTasks', compact('activeTask', 'driverID'));
    }

    public function showUpcoming(){
        $driverID = Auth::user()->empID;

        /*
            need to know if naa active rent para trapping for the "start rent" button sa upcoming tasks module
            wouldhve been nice to put these queries in a function kai theyre all somewhat the same haha    
        */
        $isActiveTask = Rent::whereIn('rent_Period_Status', ['Ongoing'])
        ->whereHas('assignments', function($query) use ($driverID){
            $query->whereIn('empID', [$driverID]);
        })
        ->exists();

        $upcomingTasks = Rent::whereIn('rent_Period_Status', ['Scheduled'])
            ->whereHas('assignments', function($query) use ($driverID){
                $query->whereIn('empID', [$driverID]);
            })
            ->with(['assignments' => function ($query) use ($driverID) {
                $query->where('empID', $driverID)
                ->with('vehicle');
            }])
            ->get();
        /*
        foreach($upcomingTasks as $task){
            foreach($task->assignments as $assignment){
                dd($assignment->vehicle->unitName);
            }
        }
        */
        return view('drivers.upcomingTasks', compact('upcomingTasks', 'isActiveTask'));
    }

    public function startRent(Request $request){
        $rentID = $request->input('rentID');
    
        $rent = Rent::find($rentID);
        $rent->update([
            'rent_Period_Status' => 'Ongoing',
        ]);

        return redirect()->route('driver.active');
    }

    public function completeRent(Request $request){
        $rentID = $request->input('rentID');

        $rent = Rent::find($rentID);
        $rent->update([
            'rent_Period_Status' => 'Complete',
        ]);

        return redirect()->route('driver.active');
    }
}
