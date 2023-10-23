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
use Illuminate\Support\Facades\Storage;

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
            ->where(function($query) {
                $query->where('maintenances.status', '=', 'Scheduled')
                      ->orWhere('maintenances.status', '=', 'In Progress');
            })
            ->select('maintenances.*', 'vehicles.pic', 'mechanics.firstName as mechanic_firstName', 'mechanics.lastName as mechanic_lastName', 'scheduledBy.firstName as scheduled_by_firstName', 'scheduledBy.lastName as scheduled_by_lastName')
            ->orderBy('scheduleDate')->paginate(10);
    
            $mechanics = Employee::where(function($query) {
                $query->where('accountType', 'Mechanic')
                      ->orWhere('accountType', 'Mechanic Outsourced');
            })->get();
            
    
        return view('employees.maintenance', compact('maintenances', 'mechanics'));
    }
    


public function history()
{   
    $maintenances = Maintenance::leftJoin('vehicles', 'maintenances.unitID', '=', 'vehicles.unitID')
        ->leftJoin('employees as mechanics', 'maintenances.mechanicAssigned', '=', 'mechanics.empID')
        ->leftJoin('employees as scheduledBy', 'maintenances.empID', '=', 'scheduledBy.empID')
        ->select('maintenances.*', 'vehicles.pic', 'mechanics.firstName as mechanic_firstName', 'mechanics.lastName as mechanic_lastName', 'scheduledBy.firstName as scheduled_by_firstName', 'scheduledBy.lastName as scheduled_by_lastName')
        ->orderBy('scheduleDate')->paginate(10);

    return view('employees.maintenanceHistory', compact('maintenances'));
}


public function create()
{
    $activeVehicles = Vehicle::where('status', 'Active')
                                    ->where('ownership_type', 'Owned')
                                    ->get();
    $mechanicEmployees = Employee::where('accountType', 'Mechanic')
                                  ->orWhere('accountType', 'Mechanic Outsourced')
                                  ->get();
    $now = now(); // Get the current date and time
    
    // Exclude vehicles that have active schedules
    $vehicles = $activeVehicles->reject(function ($vehicle) use ($now) {
        $isScheduled = Maintenance::where('unitID', $vehicle->unitID)
            ->where(function ($query) use ($now) {
                $query->where('scheduleDate', '>=', $now)
                    ->orWhere(function ($query) use ($now) {
                        $query->where('scheduleDate', '<=', $now)
                            ->where('endDate', '>=', $now);
                    });
            })
            ->whereIn('status', ['Scheduled', 'In Progress'])
            ->exists();
        return $isScheduled;
    });

    $maintenances = Maintenance::all();

    // Pass the next available schedule to the view
    return view('employees.maintenanceCreate', compact('vehicles', 'mechanicEmployees', 'maintenances'));
}



    // Handle the form submission
    public function store(Request $request)
    {    //dd($request);
        // Validate the form data
        $validatedData = $request->validate([
            'unitID' => 'required|integer',  
            'empID' => 'required|integer',
            'mechanicAssigned' => 'required|integer',
            'scheduleDateTime' => [
                'required',
                'after_or_equal:yesterday', 
            ],
            'notes' => 'nullable|string',            
        ]);
        
        
        // Create a new maintenance record
        Maintenance::create([
            'unitID' => $validatedData['unitID'],
            'empID' => $validatedData['empID'],
            'mechanicAssigned' => $validatedData['mechanicAssigned'],
            'scheduleDate' => $validatedData['scheduleDateTime'],
            'notes' => $validatedData['notes'],
            'status' => 'Scheduled',
        ]);
    
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
    } elseif ($request->input('status') === 'In Progress' && $currentStatus !== 'In Progress') {
        $maintenance->update([
            'scheduleDate' => now(), // Set the scheduleDate to the current date and time
        ]);
    }

        // Redirect back or return a JSON response as needed
        return redirect()->back()->with('success', 'Maintenance status updated successfully');
    }

    public function updateMechanic($id, $mechanic_id)
    {
        // Find the maintenance record by ID
        $maintenance = Maintenance::find($id);

        // Check if the maintenance record exists
        if (!$maintenance) {
            return redirect()->route('employee.maintenance')->with('error', 'Maintenance record not found.');
        }

        // Find the mechanic by ID
        $mechanic = Employee::find($mechanic_id);

        // Check if the mechanic exists
        if (!$mechanic) {
            return redirect()->route('employee.maintenance')->with('error', 'Mechanic not found.');
        }

        // Update the mechanic assigned to the maintenance record
        $maintenance->mechanicAssigned = $mechanic->empID;
        $maintenance->save();

        // Redirect back to the maintenance index page with a success message
        return redirect()->route('employee.maintenance')->with('success', 'Mechanic updated successfully.');
    }

    public function filter(Request $request)
{
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $status = $request->input('status');

    // Perform database query based on $startDate, $endDate, and $status
    // Fetch filtered maintenance records and return the HTML for the table rows

    // Example query (you need to modify this based on your database schema):
    $maintenances = Maintenance::whereBetween('scheduleDate', [$startDate, $endDate])
        ->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->get();

    return view('partials.maintenance-table', compact('maintenances'))->render();
}

public function getAvailableSchedules($vehicleId) {
    // Get the vehicle based on the provided ID
    $vehicle = Vehicle::findOrFail($vehicleId);

    // Get all maintenance records for the vehicle
    $maintenanceDates = $vehicle->maintenances->filter(function($maintenance) {
        return in_array($maintenance->status, ['Scheduled', 'In Progress']) &&
            $maintenance->status !== 'Cancelled';
    })->pluck('scheduleDate');
   
    // Get all booking records for the vehicle
    $bookingDates = $vehicle->vehicleAssignments->filter(function($assignment) {
        return $assignment->booking &&
            $assignment->booking->status !== 'Denied' &&
            (
                !$assignment->rent || // Check if there is no associated rent record
                !($assignment->rent->rent_Period_Status === 'Completed') ||
                !($assignment->rent->rent_Period_Status === 'Cancelled')
            );
    })->flatMap(function($assignment) {
        $startDate = \Carbon\Carbon::parse($assignment->booking->startDate);
        $endDate = \Carbon\Carbon::parse($assignment->booking->endDate);

        // Modify the start date to the beginning of the day (midnight)
        $startDate->startOfDay();

        // Modify the end date to the end of the day (just before midnight)
        $endDate->endOfDay();

        // Generate all dates in between startDate and endDate (inclusive)
        $datesInRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $datesInRange[] = $date->toDateString();
        }

        return $datesInRange;
    });

    // Merge and unique dates
    $availableSchedules = $maintenanceDates->merge($bookingDates)->unique()->values();

    // Return the available schedules as JSON response
    return response()->json($availableSchedules);
}




}
