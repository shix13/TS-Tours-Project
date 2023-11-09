<?php

namespace App\Http\Controllers\Auth;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }
    public function showRegisterForm()
    {
        return view('auth.employee-register');
    }

    public function register(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'ProfileImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the image validation rules
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'AccountType' => 'required|string|in:Manager,Clerk,Driver,Mechanic,Driver Outsourced,Mechanic Outsourced',
            'Email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($request->id)->whereNull('deleted_at'),
            ],
            'MobileNum' => [
                'required',
                'string',
                'min:11',
                'max:11',
                Rule::unique('employees')->ignore($request->id)->whereNull('deleted_at'),
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);
        

        // Check if a soft-deleted record with the same email exists
        $existingAccount = Employee::onlyTrashed()->where('email', $validatedData['Email'])->first();

        if ($existingAccount) {
            // Restore and update the soft-deleted record
            $existingAccount->restore();

            // Handle profile image upload
            if ($request->hasFile('ProfileImage')) {
                // Delete the old profile image if it exists
                if ($existingAccount->profile_img) {
                    Storage::disk('public')->delete($existingAccount->profile_img);
                } 
                
                $profileImage = $request->file('ProfileImage')->store('profile_images', 'public');
            } else {
                if ($existingAccount->profile_img) {
                    Storage::disk('public')->delete($existingAccount->profile_img);
                } 
                
                $profileImage = null;
            }

            $existingAccount->update([
                'profile_img' => $profileImage,
                'firstName' => $validatedData['FirstName'],
                'lastName' => $validatedData['LastName'],
                'accountType' => $validatedData['AccountType'],
                'mobileNum' => $validatedData['MobileNum'],
                'password' => $validatedData['password'],
            ]);

            return redirect()->route('employee.dashboard'); // Redirect to login page after registration
        } 
        else {
        // Handle profile image upload if provided
        if ($request->hasFile('ProfileImage')) {
            $profileImage = $request->file('ProfileImage')->store('profile_images', 'public');
        } else {
            $profileImage = null;
        }
        
        // Create a new Employee record
        Employee::create([
            'profile_img' => $profileImage,
            'firstName' => $validatedData['FirstName'],
            'lastName' => $validatedData['LastName'],
            'accountType' => $validatedData['AccountType'],
            'mobileNum' => $request->input('MobileNum'),
            'email' => $validatedData['Email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Redirect or respond as needed
        return redirect()->route('employee.accounts'); // Redirect to login page after registration
        }
    }

    public function showLoginForm()
    {
        return view('auth.employee-login');
    }

    public function login(Request $request)
    {   
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('employee')->attempt($credentials, $request->remember)) {
            $user = Auth::guard('employee')->user();
            
            // Check the user's account type
            if ($user->accountType === 'Clerk' || $user->accountType === 'Manager') {
                return redirect()->route('employee.dashboard');
            } else {
                // Redirect or display an error for unauthorized account types
                return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
                    'email' => 'You are not authorized to access this system.',
                ]);
            }
        }
        
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
        
    
    }

    public function showDashboard()
    {
        $schedules = $this->getAvailableSchedules();
    
        $formattedSchedules = [];

        foreach ($schedules as $vehicleId => $scheduleData) {
            foreach ($scheduleData['maintenance'] as $maintenanceDate) {
                $formattedSchedules[] = [
                    'type' => 'maintenance',
                    'title' => 'Maintenance for ' .$maintenanceDate['vehicle']->unitName .' '. $maintenanceDate['vehicle']->registrationNumber,
                    'start' => $maintenanceDate['date'],
                    'status' => $maintenanceDate['status'],
                ];
            }
            
    
            foreach ($scheduleData['booking'] as $bookingDate) {
                $formattedSchedules[] = [
                    'type' => 'booking',
                    'title' => 'Booking for ' . $bookingDate['vehicle']->unitName. ' '.$bookingDate['vehicle']->registrationNumber,
                    'ownershipType'=>$bookingDate['vehicle']->ownership_type,
                    'bookingType' => $bookingDate['booking type'],
                    'trackingID' => $bookingDate['trackingID'],
                    'start' => $bookingDate['date'],
                    'dateRange' => $bookingDate['dateRange'],
                    'bookingstatus' => $bookingDate['booking status'],
                    'rentstatus' => $bookingDate['rent status'],
                ];
            }
        }
       
        //dd($formattedSchedules);
        return view('employees.dashboard', compact('formattedSchedules'));
    }
    


    


public function getAvailableSchedules()
{
    // Get all vehicles
    $vehicles = Vehicle::with('maintenances', 'vehicleAssignments.booking')->get();
   
    $schedules = [];

    foreach ($vehicles as $vehicle) {
        // Initialize arrays for maintenance and booking schedules
        $maintenanceDates = [];
        $bookingDates = [];

        // Get all maintenance records for the vehicle
        foreach ($vehicle->maintenances as $maintenance) {
            if ($maintenance->status !== 'Cancelled') {
                $maintenanceDates[] = [
                    'type' => 'maintenance',
                    'date' => \Carbon\Carbon::parse($maintenance->scheduleDate)->toDateString(),
                    'vehicle' => $vehicle, // Include vehicle details with maintenance
                    'status' => $maintenance->status,
                ];
            }
        }

        // Get all booking records for the vehicle
        foreach ($vehicle->vehicleAssignments as $assignment) {
            if ($assignment->booking && $assignment->booking->status !== 'Denied') {
                $startDate = \Carbon\Carbon::parse($assignment->booking->startDate);
                $endDate = \Carbon\Carbon::parse($assignment->booking->endDate);
                
                $dateRange = $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d');
        
                for ($date = $startDate->copy(); $date->lte($endDate); ) {
                    $assignmentData = [
                        'type' => 'booking',
                        'date' => $date->format('Y-m-d'),
                        'dateRange' =>$dateRange,
                        'vehicle' => $vehicle, 
                        'booking type' => $assignment->booking->bookingType,
                        'trackingID' => $assignment->booking->reserveID,
                        'booking status' => $assignment->booking->status,
                    ];
        
                    // Check if there is associated rent
                    if ($assignment->rent) {
                        $assignmentData['rent status'] = $assignment->rent->rent_Period_Status;
                    } else {
                        $assignmentData['rent status'] = null; // Or you can set it to any default value
                    }
        
                    $bookingDates[] = $assignmentData;
                    $date->addDay();
                }
            }
        }
        

        // Use isset to append data without overwriting
        if (!isset($schedules[$vehicle->id])) {
            $schedules[$vehicle->id] = [
                'maintenance' => $maintenanceDates,
                'booking' => $bookingDates,
            ];
        } else {
            $schedules[$vehicle->id]['maintenance'] = array_merge($schedules[$vehicle->id]['maintenance'], $maintenanceDates);
            $schedules[$vehicle->id]['booking'] = array_merge($schedules[$vehicle->id]['booking'], $bookingDates);
        }
    }
    //dd($schedules);
    // Return the available schedules
    return $schedules;
}

    
    

    public function logout(){
       
        Auth::guard('employee')->logout();

        return redirect('/');
    }

    public function edit($empID)
{
    $employee = Employee::find($empID); 

    if (!$employee) {
        // Handle the case where the employee is not found
        return redirect()->route('employee.accounts')->with('error', 'Employee not found.');
    }
    return view('employees.accountsEdit', compact('employee'));
}

public function update(Request $request, $empID)
{
    // Validate the form data
    $request->validate([
        'ProfileImage' => 'image|max:2048',
        'FirstName' => 'required|string|max:255',
        'LastName' => 'required|string|max:255',
        'AccountType' => 'required|string',
    ]);

    // Find the employee by ID
    $employee = Employee::findOrFail($empID);
    
    // Update the employee's information
    $employee->firstName = $request->input('FirstName');
    $employee->lastName = $request->input('LastName');
    $employee->accountType = $request->input('AccountType');

    // Handle profile image upload
    if ($request->hasFile('ProfileImage')) {
        // Delete the old profile image if it exists
        if ($employee->profile_img) {
            Storage::disk('public')->delete($employee->profile_img);
        }
        // Store the new profile image
        $employee->profile_img = $request->file('ProfileImage')->store('profile_images', 'public');
    }

    // Update the password if a new one is provided
        if ($request->filled('password')) {
        $employee->password = Hash::make($request->input('password'));
    }

        $employee->save();

        return redirect()->route('employee.accounts')->with('success', 'Employee information updated successfully.');
    }

    public function delete(Request $request, $empID)
    {
    // Find the employee by ID
        $employee = Employee::find($empID);

    if (!$employee) {
        // Handle the case where the employee is not found
        // Redirect back with an error message, for example
        return redirect()->back()->with('error', 'Employee not found.');
    }

    // Perform the deletion
        $employee->delete();

    // Redirect to a success page or back to the employee list
    // Optionally, you can use ->with() to send a success message
        return redirect()->route('employee.accounts')->with('success', 'Employee deleted successfully.');
    }
    public function profile()
    {
        // Get the currently authenticated employee using the 'employee' guard
        $employee = auth()->guard('employee')->user();

        // Pass the employee's data to the 'employee.profile' view
        return view('employees.profile', compact('employee'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        

       
        if (Hash::check($request->current_password, auth('employee')->user()->password)) {
            // Current password matches; update the password
            auth('employee')->user()->update(['password' => Hash::make($request->password)]);

            return redirect()->back()->with('success', 'Password changed successfully.');
        }

        return back()->with('error', 'Current password is incorrect.');
    }
}
