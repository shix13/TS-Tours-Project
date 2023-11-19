<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VehicleAssigned;
use App\Models\Rent;
use App\Models\Booking;
use App\Models\Tariff;
use App\Models\Remittance;
use Carbon\Carbon;

class DriverController extends Controller
{
    /*
        How to know that the driver has an active assignment:
        1. Check Rents that have "ongoing" or "booked" status.
        2. Check if the correct driver is assigned by looking at vehiclesAssigned and it's empID column

    */
    public function showActive(){
        $driverID = Auth::user()->empID;
        $balance = session()->has('balance') ? session('balance') : null;
        $reserveID = session()->has('reserveID') ? session('reserveID') : null;
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

            return view('drivers.activeTasks', compact('activeTask', 'driverID', 'balance', 'reserveID'));
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

public function completeRent(Request $request)
{  
    $rentID = $request->input('rentID');

    // Find the rent
    $rent = Rent::find($rentID);

    // Check if rent exists
    if (!$rent) {
        // Handle case where rent is not found
        return redirect()->back()->with('error', 'Rent not found.');
    }

    // Get the associated booking
    $booking = $rent->booking;
    
    // Get the associated tariff
    $tariff = $booking->tariff;

    // Check if tariff exists
    if (!$tariff) {
        // Handle case where tariff is not found
        return redirect()->back()->with('error', 'Tariff not found.');
    }

    // Convert date strings to Carbon instances
    $startDate = Carbon::parse($booking->startDate)->startOfDay();
    $endDate = Carbon::parse($booking->endDate)->startOfDay();   

    // Calculate the hours rented as of the current moment
    $currentDateTime = Carbon::now();
    $hoursRented = $currentDateTime->diffInHours($booking->startDate);

    $tariffID = $rent->booking->tariffID;
    $tariff = Tariff::where('tariffID', $tariffID)->first();
    $totalHours = max(0, $hoursRented);

    if ($booking->bookingType == 'Rent'){
    // Calculate extra hours
    $tenths = floor($totalHours /  $tariff->rentPerDayHrs);
  
    $Fee = $tenths * $tariff->rate_Per_Day; 

    $extraHours = $totalHours % $tariff->rentPerDayHrs; 
  
    $extraHoursFee = ($extraHours * $tariff->rent_Per_Hour); 
   
    // Update rent status and extra hours fee
    $totalPrice = $Fee + $extraHoursFee;
        
    }else{
        $totalPrice= $tariff->do_pu;
    }
    $balance = $totalPrice - $booking->downpayment_Fee - Remittance::where('rentID', $rent->rentID)->sum('amount');
 
    if ($balance == 0){
        $rent->update([
            'payment_Status' => 'Paid'
        ]);

    }

    $rent->update([
        'rent_Period_Status' => 'Completed',
        'extra_Hours' => $totalHours,
        'total_Price' => $totalPrice,
        'balance' => $balance,
    ]);  

    return redirect()->route('driver.active')->with(['balance' => $balance, 'reserveID' => $rent->reserveID]);

}

    
}
