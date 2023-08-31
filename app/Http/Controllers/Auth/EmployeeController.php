<?php

namespace App\Http\Controllers\Auth;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


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
            'Email' => 'required|email|unique:employees',
            'MobileNum' => 'required|string|min:11|max:11',
            'password' => 'required|string|min:8|confirmed',
        ]);

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
        return redirect()->route('employee.login'); // Redirect to login page after registration
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
        return view('employee');
    }

    public function logout(){
       
        Auth::guard('employee')->logout();

        return redirect('/');
    }
}
