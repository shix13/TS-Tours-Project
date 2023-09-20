<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Customer;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout' , 'userlogout');
    }

    protected function guard()
    {
        return Auth::guard('web'); // Use the 'web' guard
    } 

    public function username()
    {
        return 'Email'; // Use the 'Email' column for login
    }

    public function userlogout(){
       
        Auth::guard('web')->logout();
        return redirect()->intended('home');
    }

    public function login(Request $request)
{   
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
            return redirect()->intended('home'); // Redirect to the dashboard upon successful login
    }
    

    return back()->withErrors(['email' => 'Invalid credentials']);
}


}
