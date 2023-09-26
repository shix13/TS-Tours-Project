<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Models\VehicleType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }
    public function vehicleIndex()
{
    $vehicles = Vehicle::paginate(5);
    return view('employees.vehicles', compact('vehicles'));
}

public function maintenanceIndex()
{
   
    return view('employees.maintenance');
}

public function create()
{
    // Fetch the vehicle types from the database
    $vehicleTypes = VehicleType::all();

    return view('employees.vehiclecreate', compact('vehicleTypes'));
}

public function store(Request $request)
{
    // Validate the form data
    $data = $request->validate([
        'pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'registrationNumber' => [
            'required',
            'string',
            'max:255',
            Rule::unique('vehicles')->ignore($request->id)->whereNull('deleted_at'),
        ],
        'unitName' => 'required|string|max:255',
        'pax' => 'required|integer|min:1',
        'specification' => 'nullable|string',
        'status' => 'required|string|in:Available,Booked,Maintenance',
        'vehicleType' => 'required|exists:vehicle_types,vehicle_Type_ID', // Validate that the selected type exists in the vehicle_types table
    ]);

    // Check if a soft-deleted record with the same registration number exists
    $existingVehicle = Vehicle::onlyTrashed()->where('registrationNumber', $data['registrationNumber'])->first();

    if ($existingVehicle) {
        // Restore and update the soft-deleted record
        $existingVehicle->restore();

        // Handle vehicle image upload
        if ($request->hasFile('pic')) {
            // Delete the old profile image if it exists
            if ($existingVehicle->pic) {
                Storage::disk('public')->delete($existingVehicle->pic);
            }
            // Store the new profile image
            $existingVehicle->pic = $request->file('pic')->store('profile_images', 'public');
        }

        $existingVehicle->update([
            'unitName' => $data['unitName'],
            'pax' => $data['pax'],
            'specification' => $data['specification'],
            'status' => $data['status'],
        ]);

        return redirect()->route('employee.vehicle')->with('success', 'Vehicle added successfully.');
    } 
    else {
    // Upload the image and store it in the 'public/vehicle' directory
    if ($request->hasFile('pic')) {
        $imagePath = $request->file('pic')->store('public/vehicle');
        $data['pic'] = str_replace('public/', '', $imagePath); // Remove 'public/' from the image path
    }

    // Create a new vehicle record with the validated data
    Vehicle::create([
        'pic' => $data['pic'],
        'registrationNumber' => $data['registrationNumber'],
        'unitName' => $data['unitName'],
        'pax' => $data['pax'],
        'specification' => $data['specification'],
        'status' => $data['status'],
        'vehicle_Type_ID' => $data['vehicleType'], // Assign the selected vehicle type
    ]);

    return redirect()->route('employee.vehicle')->with('success', 'Vehicle added successfully.');
    }
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

    public function newVType()
{
    // Fetch the existing vehicle types from the database
    $vehicleTypes = VehicleType::all();

    // Pass the existing vehicle types to the view
    return view('employees.vehicle_types_view')->with('vehicleTypes', $vehicleTypes);
}

public function VtypeCreate()
{
    return view('employees.vehicle_types_create');
}

public function VtypeStore(Request $request)
{
    // Validate the incoming request data
    $data = $request->validate([
        'vehicle_type' => 'required|string|max:255',
        'pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'required|string',
    ]);

    // Upload the image and store it in the 'public/vehicle' directory
    if ($request->hasFile('pic')) {
        $imagePath = $request->file('pic')->store('public/vehicle');
        $data['pic'] = str_replace('public/', '', $imagePath); // Remove 'public/' from the image path
    }
    //dd($data['vehicle_type'], $data['pic'], $data['description']);
    $imagePath = $data['pic'];
    // Create and store the new vehicle type in the database
    VehicleType::create([
        'vehicle_Type' => $data['vehicle_type'],
        'pic' => $imagePath,
        'description' => $data['description'],
    ]);

    // Redirect back to the form page with a success message
    return redirect()->route('vehicleTypes.view')->with('success', 'Vehicle type added successfully.');
}

public function VtypeDestroy(VehicleType $vehicle_type)
{
    try {
        $vehicle_type->delete();

        return redirect()->route('vehicleTypes.view')->with('success', 'Vehicle type deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('vehicleTypes.view')->with('error', 'Failed to delete vehicle type.');
    }
}

}