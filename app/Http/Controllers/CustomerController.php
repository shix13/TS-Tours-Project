<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Tariff;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth')->except('logout' , 'userlogout');
    }
    //
    private $sharedBooking; //Variable to be shared with other functions

    public function getVehicles(){
        $vehicles = Vehicle::all();

        return view('customers.browsevehicles', compact('vehicles'));
    }

    public function book($vehicle){
        //Get vehicle and tariff from database
        $vehicleData = Vehicle::find($vehicle);
        $tariffData = Tariff::all();
        
        return view('customers.bookvehicle', compact('vehicleData', 'tariffData'));
    }

    public function processBooking(Request $request){
        /*$validatedData = $request->validate([
            'StartDate' => 'required|date|after:today',
            'EndDate' => 'required|date|after:StartDate',
            'MobileNum' => 'required|string|max:30',
            'PickUpAddress' => 'required|string|max:255',
            'Note' => 'nullable|string|max:255',
        ]);*/

        $custID = Auth::user()->custID;
        $vehicleData = Vehicle::find($request->get('vehicleID'));
        $tariffData = Tariff::query()
            ->where('location', 'LIKE', "%{$request->get('location')}%")
            ->get();
        
        foreach($tariffData as $tariff){
            $tariffID = $tariff->tariffID;
            $tariffRate = $tariff->rate_Per_Day;
        }

        $location = $request->input('location');
        $startDate = $request->input('StartDate');
        $endDate = $request->input('EndDate');

        $subtotal = $this->processRate($tariffRate, $startDate, $endDate);
        $downpayment = $this->processDownpayment($subtotal);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('d-m-Y H:i:s');

        $bookingData = [
            "custID" => $custID,
            "unitID" => $request->get('vehicleID'),
            "tariffID" => $tariffID,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "mobileNum" => $request->get('MobileNum'),
            "pickUp_Address" => $request->get('PickUpAddress'),
            "note" => $request->get('Note'),
            "downpayment_Fee" => $downpayment,
            "subtotal" => $subtotal,
            "status" => "Pending",
        ];

        //dd($collection);
        //Create a new booking
        /*
        Reservation::create([
            "custID" => $custID,
            "unitID" => $request->get('vehicleID'),
            "tariffID" => $tariffID,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "mobileNum" => $request->get('MobileNum'),
            "pickUp_Address" => $request->get('PickUpAddress'),
            "note" => $request->get('Note'),
            "downpayment_Fee" => $downpayment,
            "subtotal" => $subtotal,
            "status" => "Available"
        ]);
        */

        return view('customers.bookcheckout', compact('bookingData', 'vehicleData', 'tariffData', 'startDate', 'endDate'));
    }

    public function storeBooking(Request $request){
        $bookingData = json_decode($request->input('booking_data'), true);
        $bookingData["gcash_RefNum"] = $request->input('gcash_RefNum');
        
        $booking = new Booking($bookingData);
        $booking->save();
        
        return redirect()->route('bookingstatus', ['booking' => $booking])->with('success', 'Your booking details have been saved successfully');
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

    public function bookingStatus($bookingID){
        $bookingData = Booking::with(['vehicle' => function ($query) {
            $query->withTrashed(); // Include soft-deleted 'vehicle' records
            }, 'tariff' => function ($query){
            $query->withTrashed(); //Include soft-deleted 'tariff' records
            }])
            ->find($bookingID);

        return view('customers.bookingstatus', compact('bookingData'));
    }

    public function bookingIndex(){
        $custID = Auth::user()->custID;

        $bookings = Booking::with(['vehicle' => function ($query) {
            $query->withTrashed(); // Include soft-deleted 'vehicle' records
            }, 'tariff' => function ($query){
            $query->withTrashed(); //Include soft-deleted 'tariff' records
            }])
            ->where('custID', 'LIKE', "%{$custID}%")
            ->withTrashed()
            ->paginate(10);
        
        return view('customers.bookingdashboard', compact('bookings'));
    }

    public function customerHome(){
        $vehicles = Vehicle::all();

        return view('customers.home', compact('vehicles'));
    }

    public function profile()
    {
        // Get the currently authenticated user
        $customer = auth()->user();


        return view('customers.myaccount', compact('customer'));
    }

    public function bookingApproved($bookingID){
        
        $bookingData = Booking::with(['vehicle' => function ($query) {
            $query->withTrashed(); // Include soft-deleted 'vehicle' records
            }, 'tariff' => function ($query){
            $query->withTrashed(); //Include soft-deleted 'tariff' records
            }])
            ->find($bookingID);

        return view('customers.bookingApproved', compact('bookingData'));
    }

    public function bookingDenied($bookingID){
        
        $bookingData = Booking::with(['vehicle' => function ($query) {
            $query->withTrashed(); // Include soft-deleted 'vehicle' records
            }, 'tariff' => function ($query){
            $query->withTrashed(); //Include soft-deleted 'tariff' records
            }])
            ->find($bookingID);

        return view('customers.bookingDenied', compact('bookingData'));
    }
}