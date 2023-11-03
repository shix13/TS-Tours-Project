<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Rent;
use App\Models\VehicleAssigned;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DriverLoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct(){
        $this->middleware('guest:driver')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.driver-login');
    }

    public function guard(){
        return \Auth::guard('driver');
    }

    public function login(Request $request)
    {   
        $this->validate($request, [
            'mobileNum' => 'required|string',
            'password' => 'required|min:8'
        ]);
          
        $credentials = $request->only('mobileNum', 'password');
        $credentials['accountType'] = 'driver';

        if (Auth::guard('driver')->attempt($credentials)) {
            return redirect()->route('driver.active');
            //return redirect()->intened(route('employee.dashboard'));
            //      intended resulted to go back to login when not logged in as customer (?)
        }

        return redirect('/driver/login')
        ->withInput($request->only('mobileNum', 'remember'))
        ->withErrors([
            'mobileNum' => 'The provided mobile number does not exist in our records.',
            'password' => 'The password is incorrect. Please try again.',
        ]);
    }

    public function logout(){
        Auth::guard('driver')->logout();
        return redirect('/driver/login');
    }
}
