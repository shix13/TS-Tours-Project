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
        /*$validatedData = $request->validate([
            'StartDate' => 'required|date|after:today',
            'EndDate' => 'required|date|after:StartDate',
            'MobileNum' => 'required|string|max:30',
            'PickUpAddress' => 'required|string|max:255',
            'Note' => 'nullable|string|max:255',
        ]);*/

        //dd($request->all());

        $location = $request->input('location');
        $tariffData = Tariff::query()
            ->where('location', 'LIKE', "%{$location}%")
            ->get();

        $bookingType = $request->input('bookingType');
        foreach($tariffData as $tariff){
            $tariffID = $tariff->tariffID;
            if($bookingType === 'Rent'){
                $tariffRate = $tariff->rate_Per_Day;

                $startDate = $request->input('StartDate');
                $endDate = $request->input('EndDate');

                $subtotal = $this->processRate($tariffRate, $startDate, $endDate);
            }
            elseif($bookingType === 'Pickup/Dropoff'){
                //Pickup/Dropoff's price does not need to be calculated by date anymore
                $startDate = $request->input('StartDate');
                $endDate = $request->input('StartDate');
                $subtotal = $tariff->do_pu;
            }
        }
        
        $downpayment = 0; // $this->processDownpayment($subtotal);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-Y H:i:s');
        
        $pickupTime = $request->input('PickupTime'); // Get pickup time from the request
        // Concatenate pickup time with start date to create the full start date and time
        $startDateTime = $startDate . ' ' . $pickupTime;
    
        $booking = new Booking();

        $bookingData = [
            "cust_first_name" => $request->input('FirstName'),
            "cust_last_name" => $request->input('LastName'),
            "cust_email" => $request->input('Email'),
            "bookingType" => $request->input('bookingType'),
            "tariffID" => $tariffID,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "mobileNum" => $request->input('MobileNum'),
            "pickUp_Address" => $request->input('PickUpAddress'),         
            "note" => $request->input('Note'),
            "downpayment_Fee" => $downpayment,
            "subtotal" => $subtotal,
            "status" => "Pending",
        ];

        // Assign values to its attributes
        $booking->cust_first_name = $request->get('FirstName');
        $booking->cust_last_name = $request->get('LastName');
        $booking->cust_email = $request->get('Email');
        $booking->bookingType = $request->get('bookingType');
        $booking->tariffID = $tariffID;
        $booking->mobileNum = $request->get('MobileNum');
        $booking->pickUp_Address = $request->get('PickUpAddress');
        $booking->note = $request->get('Note');
        $booking->downpayment_Fee = $downpayment;
        $booking->subtotal = $subtotal;
        $booking->status = "Pending";
        $booking->startDate = $startDateTime;
        $booking->endDate = $endDate;
        $booking->save();

        $reserveID = $booking->reserveID;

        $vehicleTypes = $request->input('TypeQuantity');
        
        //Process the vehicle types booked
        foreach ($vehicleTypes as $vehicleTypeId => $quantity) {
            // Create a new instance of the VehicleTypeBooked model and set the attributes
            $vehicleTypeBooked = new VehicleTypeBooked();
            $vehicleTypeBooked->vehicle_Type_ID = $vehicleTypeId;
            $vehicleTypeBooked->quantity = $quantity;
            $vehicleTypeBooked->reserveID = $reserveID;
            // Save the new record to the database
            $vehicleTypeBooked->save();
        }
        /*
        $bookingStatusUrl = route('checkbookingstatus', $reserveID);
        return Redirect::to($bookingStatusUrl);
        */
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

    public function processDownpayment($subtotal){
        $downpayment = $subtotal * 0.10;

        return $downpayment;
    }

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
        //dd($vehicleTypesBooked);
        $tariffs = $booking->tariff;
        //dd($booking);
        //dd($type);
        return view('tests.bookingstatus', compact('booking', 'vehicleTypesBooked', 'tariffs'));
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
