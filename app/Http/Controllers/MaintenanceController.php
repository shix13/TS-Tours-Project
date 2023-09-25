<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\Employee;

class MaintenanceController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }

    public function maintenanceIndex()
{
    $maintenances = Maintenance::leftJoin('vehicles', 'maintenances.unitID', '=', 'vehicles.unitID')
        ->leftJoin('employees as mechanics', 'maintenances.mechanicAssigned', '=', 'mechanics.empID')
        ->leftJoin('employees as scheduledBy', 'maintenances.empID', '=', 'scheduledBy.empID')
        ->select('maintenances.*', 'vehicles.pic', 'mechanics.firstName as mechanic_firstName','mechanics.lastName as mechanic_lastName', 'scheduledBy.firstName as scheduled_by_firstName','scheduledBy.lastName as scheduled_by_lastName')
        ->orderBy('scheduleDate')->paginate(10);

    return view('employees.maintenance', compact('maintenances'));
}


    public function create()
    {
        $vehicles = Vehicle::all();
        $maintenances = Maintenance::all();
        $employees = Employee::all();
        // You may want to fetch additional data here, e.g., a list of vehicles and mechanics
        return view('employees.maintenanceCreate', compact('vehicles', 'employees','maintenances' ));
    }

    // Handle the form submission
    public function store(Request $request)
    {   
        // Validate the form data
        $validatedData = $request->validate([
            'unitID' => 'required|integer',  
            'empID' => 'required|integer',
            'mechanicAssigned' => 'required|integer',
            'scheduleDate' => [
                'required',
                'date',
                'after_or_equal:yesterday', // Ensures the date is after or current date 
            ],
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'endDate' =>  [
                'nullable',
                'date', 
            ],
        ]);
        
        // Create a new maintenance record
        Maintenance::create($validatedData);
       
        // Redirect back with a success message
        return redirect()->route('employee.maintenance')->with('success', 'Maintenance record created successfully');
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'status' => 'required|in:Scheduled,In Progress,Cancelled,Completed',
        ]);

        // Find the maintenance record by its ID
        $maintenance = Maintenance::findOrFail($id);

        // Get the current status before updating
        $currentStatus = $maintenance->status;

        // Update the maintenance status
        $maintenance->update([
            'status' => $request->input('status'),
        ]);

        // Check if the status was changed to "Completed"
        if ($request->input('status') === 'Completed' && $currentStatus !== 'Completed') {

            $maintenance->update([
                'endDate' => now(), // Set the endDate to the current date and time
            ]);

            // Find the associated vehicle
            $vehicle = Vehicle::findOrFail($maintenance->unitID);

            // Update the vehicle status to "Available"
            $vehicle->update([
                'status' => 'Available',
            ]);
        }

        if ($request->input('status') === 'Cancelled' && $currentStatus !== 'Cancelled') {
            // Find the associated vehicle
            $vehicle = Vehicle::findOrFail($maintenance->unitID);
    
            // Update the vehicle status to "Available"
            $vehicle->update([
                'status' => 'Available',
            ]);
        }
        
        if ($request->input('status') === 'In Progress' && $currentStatus !== 'In Progress') {
            // Find the associated vehicle
            $vehicle = Vehicle::findOrFail($maintenance->unitID);
    
            // Update the vehicle status to "Maintenance"
            $vehicle->update([
                'status' => 'Maintenance',
            ]);
        }

        // Redirect back or return a JSON response as needed
        return redirect()->back()->with('success', 'Maintenance status updated successfully');
    }

}
