<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleTypeBooked;
use App\Models\Tariff;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUsMail;


class TestController extends Controller
{
    public function getVehicles(){
        $vehicleTypes = VehicleType::all();

        return view('tests.selectvehicles', compact('vehicleTypes'));
    }

    public function proceedBooking(Request $request){
        $selectedVehicleTypes = $request->input('selectedVehicleTypes');

        // Convert the comma-separated string to an array
        $selectedVehicleTypes = explode(',', $selectedVehicleTypes);

        //dd($selectedVehicleTypes);
        $vehicleTypes = [];

        foreach ($selectedVehicleTypes as $selectedType) {
            $i = 0;
            $vehicleType = VehicleType::find($selectedType[$i]); // Assuming your model is named VehicleType
            if ($vehicleType) {
                $vehicleTypes[] = $vehicleType;
            }
            $i++;
        }

        $tariffData = Tariff::all();
        return view('tests.createbooking', compact('vehicleTypes', 'tariffData'));
    }

    public function processBooking(Request $request){
        $location = $request->input('location');
        $tariff = Tariff::where('location', 'LIKE', "%{$location}%")->first();
    
        if (!$tariff) {
            return redirect()->back()->with('error', 'Tariff not found for the specified location.');
        }
    
        $bookingType = $request->input('bookingType');
        $startDate = $request->input('StartDate');
        $pickupTime = $request->input('PickupTime');
        
        if ($bookingType === 'Rent') {
            $tariffRate = $tariff->rate_Per_Day;
            $endDate = $request->input('EndDate');
    
            $startDateTime = Carbon::parse($startDate . ' ' . $pickupTime);
            $endDateTime = Carbon::parse($endDate . ' ' . $pickupTime);
    
            $diffInDays = $endDateTime->diffInDays($startDateTime);
            $endTime = $startDateTime->copy()->addDays($diffInDays)->addHours($tariff->rentPerDayHrs);
            
            $formattedStartDate = $startDateTime->format('Y-m-d H:i:s');
            $formattedEndDate = $endTime->format('Y-m-d H:i:s');
    
            $subtotal = $this->processRate($tariffRate, $startDate, $endDate);
        } elseif ($bookingType === 'Pickup/Dropoff') {
            $subtotal = $tariff->do_pu;
            $endDate = $startDate; // Assuming same start and end date for Pickup/Dropoff
            $formattedStartDate = Carbon::parse($startDate . ' ' . $pickupTime)->format('Y-m-d H:i:s');
            $formattedEndDate = $formattedStartDate; // Same start and end date for Pickup/Dropoff
        } else {
            return redirect()->back()->with('error', 'Invalid booking type.');
        }
    
        $downpayment = 0; // Calculate downpayment logic can be added here if needed
    
        $booking = new Booking([
            'cust_first_name' => $request->input('FirstName'),
            'cust_last_name' => $request->input('LastName'),
            'cust_email' => $request->input('Email'),
            'bookingType' => $bookingType,
            'tariffID' => $tariff->tariffID,
            'mobileNum' => $request->input('MobileNum'),
            'pickUp_Address' => $request->input('PickUpAddress'),
            'note' => $request->input('Note'),
            'downpayment_Fee' => $downpayment,
            'subtotal' => $subtotal,
            'status' => 'Pending',
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
        ]);
    
        $booking->save();
    
        // Process vehicle types booked (assuming 'TypeQuantity' is an array of vehicle type IDs and quantities)
        foreach ($request->input('TypeQuantity') as $vehicleTypeId => $quantity) {
            $vehicleTypeBooked = new VehicleTypeBooked([
                'vehicle_Type_ID' => $vehicleTypeId,
                'quantity' => $quantity,
                'reserveID' => $booking->reserveID,
            ]);
            $vehicleTypeBooked->save();
        }
    
        // Send booking confirmation email
        Mail::to($booking->cust_email)->send(new BookingConfirmation($booking->toArray()));
    
        return redirect()->route('checkbookingstatus', ['booking' => $booking])->with('success', 'Your booking details have been saved successfully');
    }
    

    public function processRate($tariffRate, $startDate, $endDate){
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $numberOfDays = $end->diffInDays($start);

        // If the result is 0, set it to 1
        $numberOfDays = $numberOfDays == 0 ? 1 : $numberOfDays;
        
        $subtotal = $numberOfDays * $tariffRate;

        return $subtotal;
    }

    /*public function processDownpayment($subtotal){
        $downpayment = $subtotal * 0.10;

        return $downpayment;
    }*/

    public function searchView(){
        return view('tests.searchbooking');
    }

    public function processSearch(Request $request){
        $reserveID = $request->input('reserveID');
        /*
        $booking = Booking::with(['tariff' => function ($query){
            $query->withTrashed(); //Include soft-deleted 'tariff' records
            }])
            ->find($bookingID);
        */
        $booking = Booking::where('reserveID', $reserveID)->first();
            
        if($booking){
            return redirect()->route('checkbookingstatus', $booking);
        } else {
            return redirect()->route('search')->with('error', 'Booking not found');
        }
    }

    public function bookingStatus(Booking $booking){
        $vehicleTypesBooked = $booking->vehicleTypesBooked;
        $vehiclesAssigned = $booking->vehiclesAssigned;
        if ($vehiclesAssigned->isEmpty()) {
            $vehiclesAssigned = null;
        }
        //dd($vehicleTypesBooked);
        $tariffs = $booking->tariff;
        //dd($booking);
        //dd($type);
        return view('tests.bookingstatus', compact('booking', 'vehicleTypesBooked', 'tariffs', 'vehiclesAssigned'));
    }

    public function checkout(Request $request){
        //dd($request->input('bookingID'));
        $booking = Booking::where('reserveID', $request->input('bookingID'))->first();
        ($request);

        $booking->gcash_RefNum = $request->input('gcash_RefNum');
        $booking->downpayment_Fee = $request->input('amount');
        
        $booking->save();
        return redirect()->route('checkbookingstatus', $booking);
    }

    public function sendEmail(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
        ];

        // Send email
        Mail::to('tstoursduma@gmail.com')->send(new ContactUsMail($data));

        // Redirect back with success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
