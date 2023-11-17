<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VehicleAssigned;
use App\Models\Rent;
use App\Models\Booking;
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
    $numberOfDays = $startDate->diffInDays($endDate)+1;
   

    // Calculate the hours rented as of the current moment
    $currentDateTime = Carbon::now();
    $hoursRented = $currentDateTime->diffInHours($booking->startDate);

    // Calculate the number of days the service should have taken place
    
    // Calculate the total expected hours based on the number of days
    $totalExpectedHours = $numberOfDays * $tariff->rentPerDayHrs;
   

    // Calculate extra hours
    $extraHours = max(0, $hoursRented - $totalExpectedHours);


    //dd($hoursRented, $numberOfDays, $totalExpectedHours, $extraHours, $extraHoursFee, $booking, $tariff, $rent, $request);
    
    //I honeslty have no idea what the fuck is going on with my calculation na
    /*
        Get ang tenths place diba? Kai if the extra hours go beyond 10, use the rate_per_day. 
        Pero like what if beyond 10? If 11 ang extra hours so ang first 10 is gigamit rate_per_day
        and then that 1 extra hour just uses rent_per_hour? What if 22 hours, so first 10 is rate_per_day
        then next 10 is another rate_per_day. So 20 na, then the 2 is just rent_per_hour?
    */
    $extraHours = 37;
    $tenths = floor($extraHours / 10) * 10; //floor(37/10) = 3; 3 * 10 = 30 
    $tenHours = $tenths/10; //30/10 = 3;

    $extraHoursFee = $tenHours * $tariff->rate_Per_Day; //3 * 5000 = 15000;

    $ones = $extraHours % 10; //37 % 10 = 7;

    $extraHoursFee += ($ones * $tariff->rent_Per_Hour); //15000 + (7 * 500)

    // Calculate extra hours fee
    //$extraHoursFee = $extraHours * $tariff->rent_Per_Hour;

    // Update rent status and extra hours fee
    $totalPrice = $booking->subtotal + $extraHoursFee;

    dd($booking->subtotal, $tariff->rent_Per_Hour, $extraHoursFee, $totalPrice);

    $balance = $totalPrice - $booking->downpayment_Fee - Remittance::where('rentID', $rent->rentID)->sum('amount');
  
    dd($tensPlace, $onesPlace);

    $rent->update([
        'rent_Period_Status' => 'Completed',
        'extra_Hours' => $extraHours,
        'total_Price' => $totalPrice,
        'balance' => $balance,
    ]);  

    return redirect()->route('driver.active')->with(['balance' => $balance, 'reserveID' => $rent->reserveID]);

}

    
}
