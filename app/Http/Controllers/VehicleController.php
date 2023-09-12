<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Vehicle;
use App\Models\Maintenance;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }
    public function vehicleIndex()
{
    $vehicles = Vehicle::all();
    return view('employees.vehicles', compact('vehicles'));
}

public function maintenanceIndex()
{
   
    return view('employees.maintenance');
}

public function create()
{
    return view('employees.vehiclecreate');
}


public function store(Request $request)
{
    // Validate the form data
    $data = $request->validate([
        'pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'registrationnumber' => ['required','string','max:255',Rule::unique('vehicles'),],
        'unitname' => 'required|string|max:255',
        'pax' => 'required|integer|min:1',
        'specification' => 'nullable|string',
        'status' => 'required|string|in:Available,Booked,Maintenance',
    ]);

    // Upload the image and store it in the 'public/vehicle' directory
    if ($request->hasFile('pic')) {
        $imagePath = $request->file('pic')->store('public/vehicle');
        $data['pic'] = str_replace('public/', '', $imagePath); // Remove 'public/' from the image path
    }

    // Create a new vehicle record with the validated data
    Vehicle::create([
        'pic' => $data['pic'],
        'registrationNumber' => $data['registrationnumber'],
        'unitName' => $data['unitname'],
        'pax' => $data['pax'],
        'specification' => $data['specification'],
        'status' => $data['status'],
    ]);

    return redirect()->route('employee.vehicle')->with('success', 'Vehicle added successfully.');
    }


    public function edit($id)
    {
    // Retrieve the vehicle record by ID and pass it to the edit view
    $vehicle = Vehicle::findOrFail($id);
    return view('employees.vehicleedit', compact('vehicle'));
    }

    public function destroy($id)
    {
    // Retrieve the vehicle record by ID and delete it
    $vehicle = Vehicle::findOrFail($id);
    $vehicle->delete();
    return redirect()->route('employee.vehicle')->with('success', 'Vehicle deleted successfully.');
    }


    public function update(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Add your validation rules for pic here
            'unitname' => 'required|string',
            'pax' => 'required|integer|min:1',
            'specification' => 'nullable|string',
            'status' => 'required|in:Available,Booked,Maintenance',
        ]);

        // Find the vehicle by ID
        $vehicle = Vehicle::findOrFail($id);

        // Update the vehicle attributes based on the request data
        $vehicle->unitname = $request->input('unitname');
        $vehicle->pax = $request->input('pax');
        $vehicle->specification = $request->input('specification');
        $vehicle->status = $request->input('status');

        // Handle the image upload if a new image is provided
        if ($request->hasFile('pic')) {
            $imagePath = $request->file('pic')->store('vehicle_images', 'public');
            $vehicle->pic = $imagePath;
        }

        // Save the updated vehicle
        $vehicle->save();

        // Redirect to the vehicle details page or wherever you want
        return redirect()->route('employee.vehicle', $vehicle->unitID)
            ->with('success', 'Vehicle updated successfully');
    }

}
