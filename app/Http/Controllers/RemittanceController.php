<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remittance;
use App\Models\Rent;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackEmail;

class RemittanceController extends Controller
{
    //
    public function remittanceIndex(){
        $remittance = Remittance::with([
            'clerk' => function ($query) {
                $query->withTrashed(); // Include soft-deleted clerks
            },
            'driver' => function ($query) {
                $query->withTrashed(); // Include soft-deleted drivers
            },])
            ->paginate(10);
        
        
        return view('employees.remittance', compact('remittance'));
    }

    public function create($id){

        $rent = Rent::find($id);
        
        $drivers = Employee::whereIn('accountType', ['Driver', 'Driver Outsourced'])->get();

        /*
        $drivers = Employee::where('accountType', 'Driver')
            ->get();
        */

        //dd($rent);
        return view('employees.remittanceCreate', compact('drivers','rent'));
    }

    public function store(Request $request){
    // Validate the form data
    //dd($request);
    $data = $request->validate([
        'clerk' => 'required|string|max:255',
        'clerkID' => 'required|integer',
        'driver' => 'required|integer',
        'rent' => 'required|integer',
        'receipt_num' => 'required|min:1|unique:remittances,receiptNum',
        'amount' => 'required|numeric',
        'paymentType' => 'required|in:Cash,GCash',
    ]);
    $rentData = Rent::find($data['rent']);
    $bookingData = $rentData->booking; // Define $bookingData before using it
    $email = $bookingData->cust_email; // Define $email using $bookingData

    // Create a new record in the 'rent' table
    Remittance::create([
        'clerkID' => $data['clerkID'],
        'driverID' => $data['driver'],
        'rentID' => $data['rent'],
        'receiptNum' => $data['receipt_num'],
        'amount' => $data['amount'],
        'paymentType' => $data['paymentType'],
    ]);

    $rentData = Rent::find($data['rent']);
    $bookingData = $rentData->booking;
   // dd($bookingData->cust_email);
if ($rentData->balance <= $data['amount']) {
    $rentData->payment_Status = 'Paid';
    Mail::to($email)->send(new FeedbackEmail($bookingData,$rentData)); 
}

$rentData->balance -= $data['amount'];
$rentData->save();


    return redirect()->route('employee.remittance');
    }

    public function rentIndex()
    {
        // Retrieve a list of rentals from the database 
        $rents = Rent::with('assignments')
            ->where('payment_Status','=','Pending')
            ->where('rent_Period_Status','!=','Cancelled')
            ->get();

        // Pass the data to the Blade view
        return view('employees.remittanceSelectRent', compact('rents'));
    }

    public function returnIndex()
    {
        // Retrieve a list of rentals from the database 
        $rents = Rent::with('assignments')->get();

        // Pass the data to the Blade view
        return view('employees.remittanceSelectReturnRent', compact('rents'));
    }
}
