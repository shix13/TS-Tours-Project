<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remittance;
use App\Models\Rent;
use App\Models\Employee;

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

    public function create(){
        $drivers = Employee::where('accountType', 'Driver')->get();
        return view('employees.remittancecreate', compact('drivers'));
    }

    public function store(Request $request){
    // Validate the form data
    $data = $request->validate([
        'clerk' => 'required|string|max:255',
        'clerkID' => 'required|integer',
        'driver' => 'required|integer',
        'rent' => 'required|integer',
        'receipt_num' => 'required|integer|min:1',
        'amount' => 'required|numeric',
    ]);

    // Create a new record in the 'rent' table
    Remittance::create([
        'clerkID' => $data['clerkID'],
        'driverID' => $data['driver'],
        'rentID' => $data['rent'],
        'receiptNum' => $data['receipt_num'],
        'amount' => $data['amount'],
    ]);

    $rentData = Rent::find($data['rent']);

    $rentData->payment_Status = 'Paid';
    $rentData->save();

    return redirect()->route('employee.remittance');
    }

    public function rentIndex()
    {
        // Retrieve a list of rentals from the database 
        $rents = Rent::with('driver')
            ->where('payment_Status','=','Pending')
            ->get();

        // Pass the data to the Blade view
        return view('employees.remittanceSelectRent', compact('rents'));
    }
}
