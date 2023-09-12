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
    $bookings = Booking::All();

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

        // Update the status to "Approved"
        $booking->status = 'Approved';
        $booking->save();

        // Create a new record in the 'rent' table
        Rent::create([
            'reserveID' => $booking->reserveID,
            'driverID' => request('driverID'), // Assuming 'driverID' comes from a form input
            'rent_Period_Status' => 'Booked',
            'extra_Hours' => request('extraHours'), // Assuming 'extraHours' comes from a form input
            'payment_Status' => request('paymentStatus'), // Assuming 'paymentStatus' comes from a form input
            'total_Price' => request('totalPrice'), // Assuming 'totalPrice' comes from a form input
            'balance' => request('balance'), // Assuming 'balance' comes from a form input
        ]);

        return redirect()->back()->with('success', 'Booking has been approved and saved as a rent.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while approving the booking.');
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
    $rental = Rent::find($id);

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
    $tariffLocations = Tariff::pluck('location', 'tariffID');
    // Pass the data to the Blade view
    return view('employees.rentalView', compact('rental', 'bookings', 'rents','drivers', 'availableVehicles','tariffLocations'));
}

public function update(Request $request, $id)
{
    // Validate the form data if needed
    $request->validate([
        // Define your validation rules here
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
        $booking->update([
            'startDate' => $request->input('start_date'),
            'endDate' => $request->input('end_date'),
            'mobileNum' => $request->input('mobile_num'),
            'pickUp_Address' => $request->input('pick_up_address'),
            'note' => $request->input('note'),
            'downpayment_Fee' => $request->input('downpayment_fee'),
            'gcash_RefNum' => $request->input('gcash_ref_num'),
            'subtotal' => $request->input('subtotal'),
            'status' => $request->input('status'),
        ]);

        // Attempt to update the rental information based on the form input
        $rental->update([
            'driverID' => $request->input('driver_assigned'),
            'rent_Period_Status' => $request->input('rental_status'),
            'extra_Hours' => $request->input('extra_hours'),
            'payment_Status' => $request->input('payment_status'),
            'total_Price' => $request->input('total_price'),
            'balance' => $request->input('balance'),
        ]);

        return redirect()->back()->with('success', 'Rental information updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while updating rental information: ' . $e->getMessage());
    }
}
}
