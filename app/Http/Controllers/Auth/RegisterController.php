<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Customer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'ProfileImage' => ['nullable', 'file', 'image', 'max:2048'],
            'FirstName' => ['required', 'string', 'max:255'],
            'LastName' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'MobileNum' => ['required', 'string', 'max:11', 'min:11', 'unique:customers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {  
        // Upload and store the user's profile image
    $profileImage = null;

    if (request()->hasFile('ProfileImage')) {
        $uploadedImage = request()->file('ProfileImage');
        $profileImage = $uploadedImage->store('customer_images', 'public'); // Store in "public/customer_images"
    }

    // Create the user record with the profile image path
    return Customer::create([
        'profile_img' => $profileImage,
        'firstName' => $data['FirstName'],
        'lastName' => $data['LastName'],
        'email' => $data['Email'],
        'mobileNum' => $data['MobileNum'],
        'password' => Hash::make($data['password']),
        
    ]);
    }

    protected function showRegistrationForm()
{
    if (auth()->user()->accountType === 'Manager') {
        return view('auth.register');
    } else {
        return redirect()->route('employee.dashboard'); // Redirect unauthorized users
    }
}
    
}
