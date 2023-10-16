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
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
          
        $credentials = $request->only('email', 'password');
        $credentials['accountType'] = 'driver';

        if (Auth::guard('driver')->attempt($credentials)) {
            return redirect()->route('driver.dashboard');
            //return redirect()->intened(route('employee.dashboard'));
            //      intended resulted to go back to login when not logged in as customer (?)
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(){
       
        Auth::guard('employee')->logout();

        return redirect('/home');
    }
}
