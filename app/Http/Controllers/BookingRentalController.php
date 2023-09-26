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
use App\Models\VehicleTypeBooked;
use App\Models\VehicleAssigned;

class BookingRentalController extends Controller
{
    public function bookIndex()
    {
        // Retrieve a list of drivers from the employees table
    $drivers = Employee::where('accountType', 'Driver')->get();

    // Retrieve a list of pending bookings from the database
    $pendingBookings = Booking::with(['vehicle' => function ($query) {
        $query->withTrashed(); // Include soft-deleted 'vehicle' records
        }, 'tariff' => function ($query){
        $query->withTrashed(); //Include soft-deleted 'tariff' records
        }])
        ->where('status', 'Pending')
        ->paginate(10, ['*'], 'pending');
    
    // Retrieve a list of completed bookings from the database
    $completedBookings = Booking::with(['vehicle' => function ($query) {
        $query->withTrashed(); // Include soft-deleted 'vehicle' records
        }, 'tariff' => function ($query){
        $query->withTrashed(); //Include soft-deleted 'tariff' records
        }])
        ->where('status', 'Approved')
        ->orWhere('status', 'Cancelled')
        ->orWhere('status', 'Denied')
        ->paginate(10, ['*'], 'completed'); 

    // Pass the data to the Blade view
    return view('employees.book', compact('pendingBookings', 'completedBookings', 'drivers'));
    }

    public function rentIndex()
    {
        // Retrieve a list of rentals from the database 
        //$rents = Rent::with('driver')->paginate(10);
        $rents = Rent::with([
            'assignments.employee'
        ])->paginate(10);

        // Pass the data to the Blade view
        return view('employees.rent', compact('rents'));
    }

    public function approveBooking($bookingId)
{
    try {
        $booking = Booking::findOrFail($bookingId);
        /*
        $validatedData = request()->validate([
            'driverID' => 'required|integer', 
            'extraHours' => 'nullable|numeric', 
            'paymentStatus' => 'required|in:Pending,Paid', 
            'totalPrice' => 'numeric',
            'balance' => 'numeric', 
        ]);
        */
        // Update the status to "Approved"
        $booking->status = 'Approved';
        $booking->save();

        //Change vehicle status to "Booked"
        // Create a new record in the 'rent' table
        $balance = $booking->subtotal - $booking->downpayment_Fee;
        $rent = new Rent([
            'reserveID' => $booking->reserveID,
            'rent_Period_Status' => 'Booked',
            'payment_Status' => 'Pending',
            'total_Price' => $booking->subtotal,
            'balance' => $balance,
        ]);
        $rent->save();

        //Assign the rent to vehicles assigned
        $vehiclesAssigned = VehicleAssigned::where('reserveID', $booking->reserveID)->get();
        //dd($booking->reserveID);
        foreach($vehiclesAssigned as $vehicleAssigned){
            $vehicleAssigned->update([
                'rentID' => $rent->rentID,
            ]);
        }
        
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
    $bookings = Booking::with('vehicleTypesBooked', 'tariff')->where('reserveID', $rental->reserveID)->get();
    $drivers = Employee::where('accountType', 'Driver')->get();
    
    // Retrieve a list of rents with related driver information
    $rents = Rent::with('driver')->where('rentID', $rental->rentID)->get();
    $availableVehicles = Vehicle::where('status', 'Available')->get();

    // Retrieve a list of tariff locations
    $tariffs = Tariff::All();

    $booking = Booking::find($bookings[0]->reserveID);
    // Retrieve the vehicle types assigned to this booking with their details
    $vehicleTypesBooked = $booking->vehicleTypesBooked()->with('vehicleType')->get();

    $rent = Rent::find($rents[0]->rentID);
    $vehiclesAssigned = $rent->assignments()->with('vehicle')->get();

    /*/ Now, you can access the vehicle types and their details like this:
    foreach ($vehicleTypesBooked as $typesBooked) {
    $vehicleTypes = $typesBooked->vehicleType;
    // Access vehicle type details, e.g., $vehicleType->name, $vehicleType->description, etc.
    }
    dd($vehicleTypes);*/
    // Pass the data to the Blade view

    
    return view('employees.rentalView', compact('rental', 'vehiclesAssigned', 'bookings', 'rents','drivers', 'vehicleTypesBooked',  'availableVehicles','tariffs'));
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

public function bookAssign($id)
{
    // Retrieve data from the 'booking' table where status is 'Pending' and reserveID matches $id
    $pendingBooking = Booking::where('status', 'Pending')->where('reserveID', $id)->first();
    //dd($pendingBooking->reserveID);
    // Retrieve data from the 'vehicle_types_booked' table
    $bookedTypes = VehicleTypeBooked::where('reserveID', $pendingBooking->reserveID)->get();
    //dd($bookedTypes);
    // Retrieve the list of available vehicles and employees
    $vehicles = Vehicle::where('status', 'Available')->with('vehicleType')->get(); // Eager load the 'vehicleType' relationship
    /*
    foreach($vehicles as $vehicle){
        dd($vehicle->vehicleType->vehicle_Type);
    }
    */
    $employees = Employee::where('accountType', 'Driver')->get();

    // Pass the filtered data, vehicles, and employees to the view
    return view('employees.bookAssignments', [
        'pendingBooking' => $pendingBooking,
        'bookedTypes' => $bookedTypes,
        'vehicles' => $vehicles,
        'employees' => $employees,
    ]);
}

public function storeAssigned(Request $request){
    $unitIDs = $request->input('unitID');
    $empIDs = $request->input('empID');
    $reserveID = $request->input('reserveID');
    foreach($unitIDs as $unitID){
        $i = 0;
        VehicleAssigned::create([
            'unitID' => $unitID,
            'empID' => $empIDs[$i],
            'reserveID' => $reserveID,
        ]);

        $vehicle = Vehicle::where('unitID', $unitID)->first();
        $vehicle->update([
            'status' => 'Unavailable'
        ]);
        $i++;
    }

    $booking = Booking::where('reserveID', $reserveID)->first();

    $booking->update([
        'status' => 'Pre-approved',
    ]);

    return redirect()->route('employee.booking')->with('success'.'Vehicles assigned successfully.');
}

public function preApproved(){
        // Retrieve a list of drivers from the employees table
        $drivers = Employee::where('accountType', 'Driver')->get();

    $preApprovedBookings = Booking::where('status', 'Pre-Approved')->get();
    return view('employees.preApproved', compact('preApprovedBookings','drivers'));
}
}
