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
use App\Helpers\DateHelper;
use Carbon\Carbon;

class VehicleController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }
    public function vehicleIndex()
    {
        // Retrieve all vehicles
        $vehicles = Vehicle::with(['maintenances', 'booking','vehicleAssignments'])
            ->where('status', '!=', 'Inactive')
            ->paginate(5);
    
        return view('employees.vehicles', compact('vehicles'));
    }
    
    public function retiredIndex()
{
    $vehicles = Vehicle::where('status', 'Inactive')->paginate(5);
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

    return view('employees.vehicleCreate', compact('vehicleTypes'));
}

public function store(Request $request)
{  
    try {
        // Validate the form data
        $data = $request->validate([
            'pic' => 'required|image|max:10000',
            'registrationNumber' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicles')->whereNull('deleted_at'),
            ],
            'unitName' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
            'yearModel' => 'required|integer|min:1900', // Example validation for yearModel, adjust as needed
            'color' => 'required|string|max:255',
            'ownership_type' => 'required|string|in:Owned,Outsourced',
            'outsourced_from' => 'nullable|required_if:ownership_type,Outsourced|string|max:255',
            'specification' => 'nullable|string',
            'status' => 'required|string|in:Active',
            'vehicleType' => 'required|exists:vehicle_types,vehicle_Type_ID',
        ]);
       
        // Upload the image and store it in the 'public/vehicle' directory
        $imagePath = $request->file('pic')->store('public/vehicle');
        $data['pic'] = str_replace('public/', '', $imagePath); // Remove 'public/' from the image path

        // Determine if outsourced_from should be stored based on ownership_type
        if ($data['ownership_type'] === 'Owned') {
            // If owned, set outsourced_from to null
            $data['outsourced_from'] = null;
        }

        // Create a new vehicle record with the validated data
        Vehicle::create([
            'pic' => $data['pic'],
            'registrationNumber' => $data['registrationNumber'],
            'unitName' => $data['unitName'],
            'pax' => $data['pax'],
            'yearModel' => $data['yearModel'],
            'color' => $data['color'],
            'ownership_type' => $data['ownership_type'],
            'outsourced_from' => $data['outsourced_from'],
            'specification' => $data['specification'],
            'status' => $data['status'],
            'vehicle_Type_ID' => $data['vehicleType'],
        ]);

        return redirect()->route('employee.vehicle')->with('success', 'Vehicle added successfully.');
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    }
}



    public function edit($id)
    {
    // Retrieve the vehicle record by ID and pass it to the edit view
    $vehicle = Vehicle::findOrFail($id);
    $vehicleTypes = VehicleType::all();
    return view('employees.vehicleedit', compact('vehicle', 'vehicleTypes'));
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
        'pic' => 'image|mimes:jpeg,png,jpg,gif|max:10000',
        'unitName' => 'required|string|max:255',
        'pax' => 'required|integer|min:1',
        'specification' => 'nullable|string',
        'status' => 'required|in:Active,Inactive',
        'ownership_type' => 'required|in:Owned,Outsourced',
        'outsourced_from' => 'nullable|required_if:ownership_type,Outsourced|string|max:255',
        'color' => 'required|string|max:255',
        'yearModel' => 'required|integer|min:1950', // Add validation rule for yearModel
        'vehicleType' => 'required|exists:vehicle_types,vehicle_Type_ID',
    ]);

    // Find the vehicle by ID
    $vehicle = Vehicle::findOrFail($id);

    // Update the vehicle attributes based on the request data
    $vehicle->unitName = $request->input('unitName');
    $vehicle->pax = $request->input('pax');
    $vehicle->specification = $request->input('specification');
    $vehicle->status = $request->input('status');
    $vehicle->vehicle_Type_ID = $request->input('vehicleType');
    $vehicle->ownership_type = $request->input('ownership_type');
    $vehicle->outsourced_from = ($request->input('ownership_type') === 'Outsourced') ? $request->input('outsourced_from') : null;
    $vehicle->color = $request->input('color');
    $vehicle->yearModel = $request->input('yearModel'); // Set the yearModel attribute

    // Handle the image upload if a new image is provided
    if ($request->hasFile('pic')) {
        $imagePath = $request->file('pic')->store('public/vehicle_images');
        $vehicle->pic = str_replace('public/', '', $imagePath);
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

public function VtypeEdit($vehicle_type){
    // Retrieve the vehicle record by ID and pass it to the edit view
    $vehicleType = VehicleType::findOrFail($vehicle_type);
    //dd($vehicleType);
    return view('employees.vehicle_types_edit', compact('vehicleType'));
}

public function VtypeUpdate(Request $request){
    // Validate the form data
    $request->validate([
        'pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
        'description' => 'required|string',
    ]);

    // Find the vehicle type by ID
    $vehicleType = VehicleType::findOrFail($id);

    // Update the vehicle attributes based on the request data
    $vehicleType->description = $request->input('description');

    // Handle the image upload if a new image is provided
    if ($request->hasFile('pic')) {
        $imagePath = $request->file('pic')->store('vehicle_images', 'public');
        $vehicleType->pic = $imagePath;
    }

    // Save the updated vehicle
    $vehicleType->save();

    // Redirect to the vehicle details page or wherever you want
    return redirect()->route('vehicleTypes.view')
        ->with('success', 'Vehicle updated successfully');
}


}