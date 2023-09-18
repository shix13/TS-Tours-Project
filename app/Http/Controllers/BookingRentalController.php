<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Rent;
use App\Models\Tariff;
use App\Models\Vehicle;


class BookingRentalController extends Controller
{
    public function bookIndex()
    {
        // Retrieve a list of drivers from the employees table
    $drivers = Employee::where('accountType', 'Driver')->get();

    // Retrieve a list of bookings from the database
    $bookings = Booking::with(['vehicle' => function ($query) {
        $query->withTrashed(); // Include soft-deleted 'vehicle' records
        }, 'tariff' => function ($query){
        $query->withTrashed(); //Include soft-deleted 'tariff' records
        }])
        ->get();

    // Pass the data to the Blade view
    return view('employees.book', compact('bookings', 'drivers'));
    }

    public function rentIndex()
    {
        // Retrieve a list of rentals from the database 
        $rents = Rent::with('driver')->get();

        // Pass the data to the Blade view
        return view('employees.rent', compact('rents'));
    }

    public function approveBooking($bookingId)
{
    try {
        $booking = Booking::findOrFail($bookingId);

        $validatedData = request()->validate([
            'driverID' => 'required|integer', 
            'extraHours' => 'nullable|numeric', 
            'paymentStatus' => 'required|in:Pending,Paid', 
            'totalPrice' => 'numeric',
            'balance' => 'numeric', 
        ]);
        // Update the status to "Approved"
        $booking->status = 'Approved';
        $booking->save();

        // Create a new record in the 'rent' table
        Rent::create([
            'reserveID' => $booking->reserveID,
            'driverID' => $validatedData['driverID'],
            'rent_Period_Status' => 'Booked',
            'extra_Hours' => $validatedData['extraHours'],
            'payment_Status' => $validatedData['paymentStatus'],
            'total_Price' => $validatedData['totalPrice'],
            'balance' => $validatedData['balance'],
        ]);

        return redirect()->back()->with('success', 'Booking has been approved and saved as a rent.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while approving the booking: ' . $e->getMessage());
    }
}


public function denyBooking($bookingId)
{
    try {
        $booking = Booking::findOrFail($bookingId);

        // Update the status to "Denied"
        $booking->status = 'Denied';
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been denied.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while denying the booking.');
    }
}

public function rentalView($id)
{
    // Retrieve the rental by its ID
    $rental = Rent::whereHas('booking', function ($query) use ($id) {
        $query->where('reserveID', $id);
    })->first();

    // Check if the rental exists
    if (!$rental) {
        return redirect()->back()->with('error', 'Rental not found.');
    }

    // Retrieve a list of bookings related to this rental
    $bookings = Booking::with('customer','vehicle','tariff')->where('reserveID', $rental->reserveID)->get();
    $drivers = Employee::where('accountType', 'Driver')->get();

    // Retrieve a list of rents with related driver information
    $rents = Rent::with('driver')->where('rentID', $rental->rentID)->get();
    $availableVehicles = Vehicle::where('status', 'Available')->get();

    // Retrieve a list of tariff locations
    $tariffs = Tariff::All();
    
    // Pass the data to the Blade view
    return view('employees.rentalView', compact('rental', 'bookings', 'rents','drivers', 'availableVehicles','tariffs'));
}

public function update(Request $request, $id)
{   
    
    // Validate the form data if needed
    $request->validate([
        'pickup_date' => 'required|date',
        'pickup_time' => 'required|date_format:H:i',
        'dropoff_date' => 'required|date',
        'dropoff_time' => 'required|date_format:H:i',
        'pickup_address'=> 'required',
        'vehicle_id' => 'required|exists:vehicles,unitID', 
        'tariff_id' => 'required|exists:tariffs,tariffID', 
        'driver_assigned' => 'required|exists:employees,empID', 
        'extra_hours' => 'numeric|min:0',
        'status' => 'required|in:Approved,Canceled', 
        'pickup_address' => 'required|string',
        'note' => 'nullable|string',
    ]);

    // Find the rental by ID
    $rental = Rent::find($id);

    // Check if the rental exists
    if (!$rental) {
        return redirect()->back()->with('error', 'Rental not found.');
    }

    // Find the related booking by reserveID
    $booking = Booking::where('reserveID', $rental->reserveID)->first();

    // Check if the booking exists
    if (!$booking) {
        return redirect()->back()->with('error', 'Booking not found.');
    }

    // Attempt to update the booking information based on the form input
    try {

        // Combine pickup_date and pickup_time into a single datetime string
        $pickupDateTime = $request->input('pickup_date') . ' ' . $request->input('pickup_time');
        // Use Carbon to create a datetime instance from the combined string
        $combinedStartDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $pickupDateTime);
        
        // Combine dropoff_date and dropoff_time into a single datetime string
        $dropoffDateTime = $request->input('dropoff_date') . ' ' . $request->input('dropoff_time');
        // Use Carbon to create a datetime instance from the combined string
        $combinedEndDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dropoffDateTime);


        // Attempt to update the booking information based on the form input
        $booking->update([
            'startDate' => $combinedStartDate, 
            'endDate' => $combinedEndDate,
            'tariffID' =>$request->input('tariff_id'),
            'unitID' =>$request->input('vehicle_id'),
            'pickUp_Address' => $request->input('pickup_address'),
            'note' => $request->input('note'),
            'status' => $request->input('status'),
        ]);
       
        $extraHours = $request->input('extra_hours', 0);
        $ratePerHour = $booking->tariff->rent_Per_Hour; 

       
        // Calculate the difference between the new and old extra hours
        $extraHoursDifference = $extraHours - $rental->extra_Hours;
        
        // Calculate the total price based on the new extra hours
        $newTotalPrice = $rental->total_Price + ($extraHours * $ratePerHour);
        $newBalance = $rental->balance + ($extraHours * $ratePerHour);
        
        // If the extra hours are reduced, adjust the total price and balance
     if ($extraHoursDifference < 0) {
        $extraHourRate = $ratePerHour * abs($extraHoursDifference);
        $newTotalPrice -= $extraHourRate;
        $newBalance -= $extraHourRate;
        }
        
        // Attempt to update the rental information based on the form input
        $rental->update([
            'driverID' => $request->input('driver_assigned'),
            'rent_Period_Status' => $request->input('rental_status'),
            'extra_Hours' => $request->input('extra_hours'),
            'total_Price' => $newTotalPrice, 
            'balance' => $newBalance,
        ]);
    
        return redirect()->back()->with('success', 'Rental information updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while updating rental information: ' . $e->getMessage());
    }
    
}
}
