<?php

namespace App\Http\Controllers\Auth;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'AccountType' => 'required|string|in:Manager,Clerk,Driver,Mechanic',
            'Email' => 'required|email', Rule::unique('employees')->ignore($request->id)->whereNull('deleted_at'),
            'MobileNum' => 'required|string|min:11|max:11',
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
        return redirect()->route('employee.dashboard'); // Redirect to login page after registration
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
            'password' => 'required|min:8'
        ]);
                
        if (Auth::guard('employee')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('employee.dashboard');
            //return redirect()->intened(route('employee.dashboard'));
            //      intended resulted to go back to login when not logged in as customer (?)
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    
    }

    public function showDashboard()
    {
        return view('employees.dashboard');
    }

    public function logout(){
       
        Auth::guard('employee')->logout();

        return redirect('/home');
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
        'Email' => 'required|email|unique:employees,email,' . $empID . ',empID',
        'MobileNum' => 'required|string|max:20',
        'AccountType' => 'required|string',
    ]);

    // Find the employee by ID
    $employee = Employee::findOrFail($empID);
    
    // Update the employee's information
    $employee->firstName = $request->input('FirstName');
    $employee->lastName = $request->input('LastName');
    $employee->email = $request->input('Email');
    $employee->mobileNum = $request->input('MobileNum');
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
}
